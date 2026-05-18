<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\PaymentTransaction;
use App\Services\SquadService;

class SquadPaymentController extends Controller
{
    /**
     * Initiate a Squad payment and return a checkout URL.
     */
    public function initiate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'email' => 'required|email',
            'callback_url' => 'nullable|url',
            'customer_name' => 'nullable|string|max:255',
        ]);

        $squadKey = config('services.squad.secret_key');
        $callbackUrl = $request->input('callback_url', url('/?payment_status=success'));

        if (empty($squadKey)) {
            Log::error('Squad payment initiation failed: SQUAD_SECRET_KEY not configured');
            return response()->json([
                'message' => 'Payment provider configuration is incomplete. Please contact support.'
            ], 500);
        }

        // Find or create donor
        $donor = Donor::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->input('customer_name', 'Anonymous Donor'),
                'surname' => '',
                'other_name' => '',
                'phone' => null,
                'faculty_id' => null,
                'department_id' => null,
                'donor_type' => 'addressable_alumni', // Default type
            ]
        );

        // Create donation record
        $amountNaira = $request->amount / 100; // Convert from kobo to naira
        $donation = Donation::create([
            'donor_id' => $donor->id,
            'project_id' => null, // General donation
            'amount' => $amountNaira,
            'payment_reference' => 'ABU_SQUAD_' . time() . '_' . uniqid(),
            'status' => 'pending',
            'type' => 'endowment', // Default to endowment
            'frequency' => 'onetime',
            'endowment' => 'yes',
        ]);

        PaymentTransaction::create([
            'donation_id' => $donation->id,
            'donor_id' => $donor->id,
            'project_id' => $donation->project_id,
            'payment_gateway' => 'squad',
            'category' => $donation->project_id ? 'project' : 'general',
            'event_type' => 'payment.initialized',
            'payment_reference' => $donation->payment_reference,
            'gateway_reference' => null,
            'amount' => $donation->amount,
            'currency' => 'NGN',
            'status' => 'pending',
            'gateway_status' => 'initialized',
            'channel' => null,
            'fee' => 0,
            'metadata' => [
                'donor_status' => 'new',
                'flow' => 'new_user_general',
            ],
        ]);

        $payload = [
            'amount' => intval($request->input('amount')),
            'email' => $request->input('email'),
            'currency' => 'NGN',
            'initiate_type' => 'inline',
            'transaction_ref' => $donation->payment_reference,
            'callback_url' => $callbackUrl,
        ];

        if ($request->filled('customer_name')) {
            $payload['customer_name'] = $request->input('customer_name');
        }

        try {
            $response = Http::withToken($squadKey)
                ->acceptJson()
                ->timeout(30)
                ->post(config('services.squad.base_url') . '/transaction/initiate', $payload);

            if ($response->failed()) {
                // Update donation status to failed
                $donation->update(['status' => 'failed']);

                PaymentTransaction::where('donation_id', $donation->id)
                    ->where('event_type', 'payment.initialized')
                    ->update([
                        'status' => 'failed',
                        'gateway_status' => 'failed',
                        'response_payload' => json_encode($response->json()),
                        'message' => $response->json()['message'] ?? null,
                    ]);

                Log::error('Squad payment initiation failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'payload' => $payload,
                ]);

                return response()->json([
                    'message' => 'Unable to initiate payment. Please try again later.',
                    'details' => $response->json(),
                ], 500);
            }

            $data = $response->json();
            $checkoutUrl = data_get($data, 'data.checkout_url');
            $squadTransactionRef = data_get($data, 'data.transaction_ref');

            if (!$checkoutUrl) {
                $donation->update(['status' => 'failed']);

                PaymentTransaction::where('donation_id', $donation->id)
                    ->where('event_type', 'payment.initialized')
                    ->update([
                        'status' => 'failed',
                        'gateway_status' => 'failed',
                        'response_payload' => json_encode($data),
                        'message' => 'Missing checkout_url from Squad response',
                    ]);

                Log::error('Squad payment initiation missing checkout URL', [
                    'response' => $data,
                    'payload' => $payload,
                ]);

                return response()->json([
                    'message' => 'Payment provider did not return a checkout URL.',
                    'details' => $data,
                ], 500);
            }

            // Update donation with gateway_reference
            $donation->update(['gateway_reference' => $squadTransactionRef]);

            PaymentTransaction::where('donation_id', $donation->id)
                ->where('event_type', 'payment.initialized')
                ->update([
                    'gateway_reference' => $squadTransactionRef,
                    'response_payload' => json_encode($data),
                ]);

            return response()->json([
                'checkout_url' => $checkoutUrl,
                'transaction_ref' => $squadTransactionRef,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            $donation->update(['status' => 'failed']);
            Log::error('Squad payment initiation exception', [
                'message' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return response()->json([
                'message' => 'Unexpected error while initiating Squad payment.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle Squad webhook
     */
    public function webhook(Request $request)
    {
        $data = $request->all();
        Log::info('Squad webhook received', $data);

        $event = $data['event'] ?? null;
        $transactionRef = $data['transaction_ref'] ?? null;

        if (!$transactionRef) {
            Log::warning('Squad webhook: Missing transaction_ref');
            return response()->json(['message' => 'Missing transaction_ref'], 200);
        }

        // Find donation by payment_reference
        $donation = Donation::where('payment_reference', $transactionRef)->first();

        if (!$donation) {
            Log::warning('Squad webhook: Donation not found', ['transaction_ref' => $transactionRef]);
            return response()->json(['message' => 'Donation not found'], 200);
        }

        // Load relationships
        $donation->load('donor', 'project');

        if ($event === 'charge.success') {
            // Update donation status
            $donation->update([
                'status' => 'completed',
                'verified_at' => now(),
                'paid_at' => $data['paid_at'] ?? now()
            ]);

            // Create payment transaction record
            PaymentTransaction::create([
                'donation_id' => $donation->id,
                'donor_id' => $donation->donor_id,
                'project_id' => $donation->project_id,
                'payment_gateway' => 'squad',
                'category' => $donation->project_id ? 'project' : 'general',
                'event_type' => 'charge.success',
                'payment_reference' => $transactionRef,
                'gateway_reference' => $data['id'] ?? null,
                'amount' => $data['amount'] ?? $donation->amount,
                'currency' => 'NGN',
                'status' => 'completed',
                'gateway_status' => $data['status'] ?? 'success',
                'channel' => $data['channel'] ?? null,
                'fee' => $data['fee'] ?? 0,
                'response_payload' => json_encode($data),
            ]);

            // Update project raised amount if donation has project_id
            if ($donation->project_id) {
                $this->updateProjectRaised($donation->project_id, $donation->id);
            }

            // Send thank you email (reuse from PaymentController)
            $this->sendThankYouEmail($donation);

            Log::info('Squad webhook: Payment marked as completed', [
                'donation_id' => $donation->id,
                'transaction_ref' => $transactionRef,
                'amount' => $data['amount'] ?? null
            ]);
        } elseif ($event === 'charge.failed') {
            $donation->update(['status' => 'failed']);

            // Create failed transaction record
            PaymentTransaction::create([
                'donation_id' => $donation->id,
                'donor_id' => $donation->donor_id,
                'project_id' => $donation->project_id,
                'payment_gateway' => 'squad',
                'category' => $donation->project_id ? 'project' : 'general',
                'event_type' => 'charge.failed',
                'payment_reference' => $transactionRef,
                'gateway_reference' => $data['id'] ?? null,
                'amount' => $data['amount'] ?? $donation->amount,
                'currency' => 'NGN',
                'status' => 'failed',
                'gateway_status' => $data['status'] ?? 'failed',
                'channel' => $data['channel'] ?? null,
                'fee' => $data['fee'] ?? 0,
                'response_payload' => json_encode($data),
            ]);

            Log::info('Squad webhook: Payment marked as failed', [
                'donation_id' => $donation->id,
                'transaction_ref' => $transactionRef
            ]);
        }

        return response()->json(['message' => 'Webhook processed'], 200);
    }

    /**
     * Verify Squad payment
     */
    public function verify(Request $request, $reference)
    {
        $squadService = new SquadService();

        // Find donation by payment_reference or gateway_reference
        $donation = Donation::where('payment_reference', $reference)
            ->orWhere('gateway_reference', $reference)
            ->first();

        if (!$donation) {
            Log::warning('Squad verify: Donation not found', ['reference' => $reference]);
            return response()->json([
                'success' => false,
                'message' => 'Donation not found for this reference.',
            ], 404);
        }

        $verifyRef = $donation->gateway_reference ?: $donation->payment_reference;
        $result = $squadService->verifyTransaction($verifyRef);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['error'] ?? 'Unable to verify payment.',
                'details' => $result['data'] ?? null,
            ], 500);
        }

        $transactionData = $result['data'] ?? [];
        $status = strtolower($transactionData['transaction_status'] ?? $transactionData['status'] ?? 'unknown');

        // Load relationships
        $donation->load('donor', 'project');

        if ($status === 'success') {
            if ($donation->status !== 'completed') {
                $donation->update([
                    'status' => 'completed',
                    'verified_at' => now(),
                    'paid_at' => $transactionData['transaction_date'] ?? now(),
                ]);
            }

            $existing = PaymentTransaction::where('payment_reference', $donation->payment_reference)
                ->where('event_type', 'charge.success')
                ->first();

            if (!$existing) {
                PaymentTransaction::create([
                    'donation_id' => $donation->id,
                    'donor_id' => $donation->donor_id,
                    'project_id' => $donation->project_id,
                    'payment_gateway' => 'squad',
                    'category' => $donation->project_id ? 'project' : 'general',
                    'event_type' => 'charge.success',
                    'payment_reference' => $donation->payment_reference,
                    'gateway_reference' => $transactionData['transaction_ref'] ?? $verifyRef,
                    'amount' => $transactionData['amount'] ?? $donation->amount,
                    'currency' => 'NGN',
                    'status' => 'completed',
                    'gateway_status' => $status,
                    'channel' => $transactionData['payment_method'] ?? null,
                    'fee' => $transactionData['fee'] ?? 0,
                    'response_payload' => json_encode($result['raw'] ?? $transactionData),
                ]);

                if ($donation->project_id) {
                    $this->updateProjectRaised($donation->project_id, $donation->id);
                }

                $this->sendThankYouEmail($donation);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully.',
                'data' => $transactionData,
            ]);
        }

        if (in_array($status, ['failed', 'declined'])) {
            if ($donation->status !== 'failed') {
                $donation->update(['status' => 'failed']);
            }

            $existing = PaymentTransaction::where('payment_reference', $donation->payment_reference)
                ->where('event_type', 'charge.failed')
                ->first();

            if (!$existing) {
                PaymentTransaction::create([
                    'donation_id' => $donation->id,
                    'donor_id' => $donation->donor_id,
                    'project_id' => $donation->project_id,
                    'payment_gateway' => 'squad',
                    'category' => $donation->project_id ? 'project' : 'general',
                    'event_type' => 'charge.failed',
                    'payment_reference' => $donation->payment_reference,
                    'gateway_reference' => $transactionData['transaction_ref'] ?? $verifyRef,
                    'amount' => $transactionData['amount'] ?? $donation->amount,
                    'currency' => 'NGN',
                    'status' => 'failed',
                    'gateway_status' => $status,
                    'channel' => $transactionData['payment_method'] ?? null,
                    'fee' => $transactionData['fee'] ?? 0,
                    'response_payload' => json_encode($result['raw'] ?? $transactionData),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment failed.',
                'data' => $transactionData,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to determine payment status.',
            'data' => $transactionData,
        ], 400);
    }

    /**
     * Update project raised amount
     */
    private function updateProjectRaised($projectId, $donationId)
    {
        $project = \App\Models\Project::find($projectId);
        if ($project) {
            $totalRaised = \App\Models\Donation::where('project_id', $projectId)
                ->where('status', 'completed')
                ->sum('amount');
            $project->update(['raised' => $totalRaised]);
        }
    }

    /**
     * Send thank you email
     */
    private function sendThankYouEmail($donation)
    {
        try {
            // Check if the mail class exists
            if (class_exists('App\\Mail\\ThankYouForDonation')) {
                Mail::to($donation->donor->email)->send(new \App\Mail\ThankYouForDonation($donation));
            } else {
                // Fallback: Log that email wasn't sent
                Log::info('Thank you email not sent (ThankYouForDonation class not found)', ['donation_id' => $donation->id]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send thank you email', ['donation_id' => $donation->id, 'error' => $e->getMessage()]);
        }
    }
}
