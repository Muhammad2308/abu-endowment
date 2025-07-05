<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Donation;
use App\Models\Donor;

class PaymentController extends Controller
{
    protected $paystackSecretKey;
    protected $paystackPublicKey;

    public function __construct()
    {
        $this->paystackSecretKey = config('services.paystack.secret_key');
        $this->paystackPublicKey = config('services.paystack.public_key');
    }

    /**
     * Initialize Paystack transaction
     */
    public function initialize(Request $request)
    {
        $request->validate([
            'donor_id' => 'required|exists:donors,id',
            'amount' => 'required|numeric|min:100', // Minimum 100 kobo (₦1)
            'frequency' => 'required|in:onetime,recurring',
            'endowment' => 'required|in:yes,no',
            'project' => 'nullable|string|max:255',
            'email' => 'required|email',
            'callback_url' => 'required|url',
        ]);

        try {
            $donor = Donor::findOrFail($request->donor_id);
            
            // Create donation record
            $donation = Donation::create([
                'donor_id' => $request->donor_id,
                'amount' => $request->amount,
                'frequency' => $request->frequency,
                'endowment' => $request->endowment,
                'project' => $request->project,
                'status' => 'pending',
            ]);

            // Initialize Paystack transaction
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->paystackSecretKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.paystack.co/transaction/initialize', [
                'email' => $request->email,
                'amount' => $request->amount * 100, // Convert to kobo
                'reference' => 'ABU_' . time() . '_' . $donation->id,
                'callback_url' => $request->callback_url,
                'metadata' => [
                    'donation_id' => $donation->id,
                    'donor_id' => $request->donor_id,
                    'project' => $request->project,
                    'frequency' => $request->frequency,
                    'endowment' => $request->endowment,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Update donation with payment reference
                $donation->update([
                    'payment_reference' => $data['data']['reference']
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment initialized successfully',
                    'data' => [
                        'authorization_url' => $data['data']['authorization_url'],
                        'access_code' => $data['data']['access_code'],
                        'reference' => $data['data']['reference'],
                        'donation_id' => $donation->id,
                    ]
                ]);
            } else {
                Log::error('Paystack initialization failed', $response->json());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to initialize payment'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Payment initialization error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while initializing payment'
            ], 500);
        }
    }

    /**
     * Verify payment status
     */
    public function verify($reference)
    {
        try {
            $donation = Donation::where('payment_reference', $reference)->first();
            
            if (!$donation) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Donation not found'
                ], 404);
            }

            // Verify with Paystack
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->paystackSecretKey,
            ])->get("https://api.paystack.co/transaction/verify/{$reference}");

            if ($response->successful()) {
                $data = $response->json();
                $transaction = $data['data'];

                // Update donation status based on Paystack response
                if ($transaction['status'] === 'success') {
                    $donation->update([
                        'status' => 'success'
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Payment verified successfully',
                        'data' => [
                            'donation' => $donation,
                            'transaction' => $transaction
                        ]
                    ]);
                } else {
                    $donation->update([
                        'status' => 'failed'
                    ]);

                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Payment verification failed',
                        'data' => [
                            'donation' => $donation,
                            'transaction' => $transaction
                        ]
                    ]);
                }
            } else {
                Log::error('Paystack verification failed', $response->json());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to verify payment'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Payment verification error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while verifying payment'
            ], 500);
        }
    }

    /**
     * Handle Paystack webhooks
     */
    public function webhook(Request $request)
    {
        try {
            $signature = $request->header('X-Paystack-Signature');
            $payload = $request->getContent();
            
            // Verify webhook signature
            $computedSignature = hash_hmac('sha512', $payload, $this->paystackSecretKey);
            
            if (!hash_equals($signature, $computedSignature)) {
                Log::warning('Invalid webhook signature');
                return response()->json(['status' => 'error'], 400);
            }

            $event = $request->input('event');
            $data = $request->input('data');

            if ($event === 'charge.success') {
                $reference = $data['reference'];
                $donation = Donation::where('payment_reference', $reference)->first();

                if ($donation) {
                    $donation->update([
                        'status' => 'success'
                    ]);

                    Log::info("Payment successful for donation ID: {$donation->id}");
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Webhook processing error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
} 