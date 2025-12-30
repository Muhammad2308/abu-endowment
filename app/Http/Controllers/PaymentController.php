<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\DeviceSession;
use App\Models\Project;

class PaymentController extends Controller
{
    protected $paystackSecretKey;
    protected $paystackPublicKey;

    public function __construct()
    {
        $this->paystackSecretKey = config('services.paystack.secret_key');
        $this->paystackPublicKey = config('services.paystack.public_key');
        
        // Validate Paystack configuration
        if (!$this->paystackSecretKey || !$this->paystackPublicKey) {
            Log::error('Paystack configuration missing', [
                'secret_key_exists' => !empty($this->paystackSecretKey),
                'public_key_exists' => !empty($this->paystackPublicKey)
            ]);
        }
    }

    /**
     * Initialize payment (NO AUTH REQUIRED)
     */
    
    public function initialize(Request $request)
    {
        try {
            // Validate Paystack configuration first
            if (empty($this->paystackSecretKey)) {
                Log::error('Paystack secret key is missing');
                return response()->json([
                    'success' => false,
                    'message' => 'Payment gateway configuration error. Please contact support.',
                    'error' => 'PAYSTACK_SECRET_KEY not configured'
                ], 500);
            }

            $request->validate([
                'email' => 'required|email',
                'amount' => 'required|numeric|min:100', // Minimum 100 naira (Paystack requirement)
                'metadata' => 'required|array',
                'metadata.name' => 'required|string',
                'metadata.surname' => 'required|string',
                'metadata.other_name' => 'nullable|string',
                'metadata.phone' => 'nullable|string', // Phone is now optional
                'metadata.donor_id' => 'nullable|exists:donors,id', // Authenticated user's donor_id
                'metadata.endowment' => 'required|in:yes,no',
                'metadata.type' => 'nullable|in:endowment,project',
                'metadata.project_id' => 'nullable|exists:projects,id',
                'device_fingerprint' => 'nullable|string', // Now optional - frontend uses authenticated sessions
                'callback_url' => 'required|url',
            ]);

            $metadata = $request->metadata;
            $deviceFingerprint = $request->device_fingerprint;

            // Add validation for endowment based on project_id
            if (
                (empty($metadata['project_id']) && $metadata['endowment'] !== 'yes') ||
                (!empty($metadata['project_id']) && $metadata['endowment'] !== 'no')
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid donation type: If project_id is null, endowment must be yes. If project_id is set, endowment must be no.'
                ], 422);
            }

            // Extract type from metadata - REQUIRED for donations table
            $donationType = $metadata['type'] ?? null;
            if (!$donationType) {
                // Fallback: determine from endowment field
                $donationType = ($metadata['endowment'] === 'yes') ? 'endowment' : 'project';
            }

            // Find or create donor - prioritize authenticated donor_id from metadata
            $donor = $this->findOrCreateDonor($deviceFingerprint, $metadata, $request->email);

            $amountNaira = $request->amount; // e.g., 1000
            $amountKobo = $this->nairaToKobo($amountNaira);

            // 1. Create donation record FIRST (store naira)
            $donation = Donation::create([
                'donor_id' => $donor->id,
                'project_id' => $metadata['project_id'] ?? null,
                'amount' => $amountNaira, // store in naira
                'type' => $donationType, // CRITICAL: Extract from metadata
                'frequency' => 'onetime', // Default to onetime
                'endowment' => $metadata['endowment'],
                'status' => 'pending',
                'payment_reference' => 'ABU_' . time() . '_' . $donor->id,
            ]);

            // 2. Initialize Paystack transaction with donation payment_reference
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->paystackSecretKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.paystack.co/transaction/initialize', [
                'email' => $request->email,
                'amount' => $amountKobo, // send kobo to Paystack
                'reference' => $donation->payment_reference,
                'callback_url' => $request->callback_url,
                'metadata' => [
                    'donation_id' => $donation->id,
                    'donor_id' => $donor->id,
                    'project_id' => $metadata['project_id'] ?? null,
                    'endowment' => $metadata['endowment'],
                    // Only include device_fingerprint if provided (for backward compatibility)
                    ...($deviceFingerprint ? ['device_fingerprint' => $deviceFingerprint] : []),
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // 3. Update donation with Paystack reference
                $donation->update([
                    'payment_reference' => $data['data']['reference']
                ]);

                // Create/Update device session only if device_fingerprint is provided
                if ($deviceFingerprint) {
                    $this->createDeviceSession($donor, $deviceFingerprint, $request);
                }

                Log::info('Payment initialized successfully', [
                    'donation_id' => $donation->id,
                    'donor_id' => $donor->id,
                    'amount' => $request->amount,
                    'endowment' => $metadata['endowment'],
                    'project_id' => $metadata['project_id'] ?? null,
                    'paystack_reference' => $data['data']['reference']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment initialized successfully',
                    'data' => [
                        'authorization_url' => $data['data']['authorization_url'],
                        'access_code' => $data['data']['access_code'],
                        'reference' => $data['data']['reference'],
                        'donation_id' => $donation->id,
                        'donor' => [
                            'id' => $donor->id,
                            'name' => $donor->name,
                            'email' => $donor->email,
                            'phone' => $donor->phone
                        ]
                    ]
                ]);
            } else {
                // If Paystack fails, delete the donation record
                $donation->delete();
                
                $errorResponse = $response->json();
                $errorMessage = $errorResponse['message'] ?? 'Unknown error';
                $errorCode = $errorResponse['code'] ?? 'unknown_error';
                
                Log::error('Paystack initialization failed', [
                    'status' => $response->status(),
                    'error' => $errorResponse,
                    'secret_key_set' => !empty($this->paystackSecretKey),
                    'secret_key_length' => strlen($this->paystackSecretKey ?? ''),
                    'amount_kobo' => $amountKobo,
                ]);

                // Provide user-friendly error messages
                if ($errorCode === 'invalid_Key') {
                    $errorMessage = 'Payment gateway configuration error. Please contact support.';
                } elseif ($errorCode === 'invalid_amount') {
                    $errorMessage = 'Invalid payment amount. Minimum amount is ₦100.';
                }
                
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error' => $errorCode,
                    'details' => $errorResponse
                ], 500);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Payment initialization validation failed', [
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Payment initialization error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while initializing payment. Please try again or contact support.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Verify payment callback
     */
    public function verify(Request $request, $reference = null)
    {
        try {
            // Get reference from route parameter or query string
            $reference = $reference ?? $request->query('reference') ?? $request->input('reference');
            
            if (!$reference) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment reference is required'
                ], 400);
            }

            // Log verification attempt
            Log::info('Payment verification attempt', [
                'reference' => $reference,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Verify with Paystack
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->paystackSecretKey,
            ])->get("https://api.paystack.co/transaction/verify/{$reference}");

            if ($response->successful()) {
                $responseData = $response->json();
                $data = $responseData['data'] ?? null;
                
                // Log Paystack response for debugging (including test mode indicators)
                Log::info('Paystack verification response', [
                    'reference' => $reference,
                    'status' => $data['status'] ?? 'unknown',
                    'gateway_response' => $data['gateway_response'] ?? null,
                    'channel' => $data['channel'] ?? null,
                    'paid_at' => $data['paid_at'] ?? null,
                    'amount' => $data['amount'] ?? null,
                    'authorization' => isset($data['authorization']) ? 'present' : 'missing',
                    'customer' => isset($data['customer']) ? 'present' : 'missing',
                    'domain' => $data['domain'] ?? null, // 'test' or 'live' for Paystack
                    'full_response' => $data // Log full response for debugging test mode
                ]);
                
                if (!$data) {
                    Log::error('Paystack verification: Missing data in response', [
                        'reference' => $reference,
                        'response' => $responseData
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid response from Paystack'
                    ], 400);
                }
                
                // Find donation by reference with relationships
                // Try both the provided reference and the Paystack reference
                $donation = Donation::with('donor', 'project')
                    ->where('payment_reference', $reference)
                    ->orWhere('payment_reference', $data['reference'] ?? '')
                    ->first();
                
                if (!$donation) {
                    Log::warning('Payment verification: Donation not found', [
                        'reference' => $reference,
                        'paystack_reference' => $data['reference'] ?? null,
                        'all_donations' => Donation::pluck('payment_reference')->toArray()
                    ]);
                    
                    // If redirect is requested, redirect with error
                    if ($request->has('redirect')) {
                        return redirect($request->query('redirect') . '&payment_status=error&message=' . urlencode('Donation not found'));
                    }
                    return response()->json([
                        'success' => false,
                        'message' => 'Donation not found'
                    ], 404);
                }

                // Check Paystack status - Paystack returns 'success' for successful payments
                // Also check gateway_response for additional confirmation
                // In test mode, status might be different, so check multiple indicators
                $status = strtolower($data['status'] ?? '');
                $gatewayResponse = isset($data['gateway_response']) ? strtolower($data['gateway_response']) : '';
                $channel = strtolower($data['channel'] ?? '');
                
                // Multiple checks for success:
                // 1. Status is 'success'
                // 2. Gateway response contains 'successful'
                // 3. Channel exists and transaction is not pending/failed
                // 4. Amount is greater than 0 (indicates payment was processed)
                $isSuccessful = ($status === 'success') || 
                               ($gatewayResponse === 'successful' || strpos($gatewayResponse, 'success') !== false) ||
                               (($channel !== '' && $status !== 'failed' && $status !== 'pending') && 
                                isset($data['amount']) && $data['amount'] > 0);
                
                // Additional check: If transaction has authorization and amount > 0, consider it successful
                if (!$isSuccessful && isset($data['authorization']) && isset($data['amount']) && $data['amount'] > 0) {
                    $isSuccessful = true;
                    Log::info('Payment marked as successful based on authorization and amount', [
                        'reference' => $reference,
                        'amount' => $data['amount'],
                        'has_authorization' => !empty($data['authorization'])
                    ]);
                }
                
                // Update donation status
                $oldStatus = $donation->status;
                $donation->update([
                    'status' => $isSuccessful ? 'completed' : 'failed',
                    'verified_at' => now(),
                    'paid_at' => $isSuccessful ? ($data['paid_at'] ?? now()) : null
                ]);

                // Update payment_reference if Paystack returned a different one
                if (isset($data['reference']) && $data['reference'] !== $donation->payment_reference) {
                    $donation->update(['payment_reference' => $data['reference']]);
                    Log::info('Payment reference updated', [
                        'old_reference' => $reference,
                        'new_reference' => $data['reference']
                    ]);
                }

                Log::info('Donation status updated', [
                    'donation_id' => $donation->id,
                    'old_status' => $oldStatus,
                    'new_status' => $donation->status,
                    'is_successful' => $isSuccessful,
                    'paystack_status' => $data['status'] ?? 'unknown',
                    'gateway_response' => $data['gateway_response'] ?? null,
                    'channel' => $data['channel'] ?? null,
                    'domain' => $data['domain'] ?? null,
                    'test_mode' => ($data['domain'] ?? '') === 'test'
                ]);

                // Update project raised amount if payment is successful and has project_id
                if ($isSuccessful && $donation->project_id) {
                    $this->updateProjectRaised($donation->project_id, $donation->id);
                }

                // Send thank you email if payment is successful
                if ($isSuccessful) {
                    $this->sendThankYouEmail($donation);
                }

                // If redirect is requested (from frontend), redirect to homepage with success
                if ($request->has('redirect')) {
                    $redirectUrl = $request->query('redirect');
                    $status = $isSuccessful ? 'success' : 'failed';
                    $donorName = $donation->donor ? urlencode($donation->donor->full_name ?? $donation->donor->name) : '';
                    $amount = isset($data['amount']) ? ($data['amount'] / 100) : $donation->amount;
                    return redirect($redirectUrl . '&payment_status=' . $status . '&reference=' . ($data['reference'] ?? $reference) . '&donor_name=' . $donorName . '&amount=' . $amount);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Payment verified successfully',
                    'data' => [
                        'status' => $donation->status,
                        'paystack_status' => $data['status'] ?? 'unknown',
                        'amount' => isset($data['amount']) ? ($data['amount'] / 100) : $donation->amount,
                        'donation_id' => $donation->id,
                        'reference' => $data['reference'] ?? $reference
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Payment verification error', [
                'error' => $e->getMessage(),
                'reference' => $request->query('reference')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while verifying payment'
            ], 500);
        }
    }

    /**
     * Handle Paystack webhook
     */
    public function webhook(Request $request)
    {
        try {
            // Get the webhook secret from config
            $webhookSecret = config('services.paystack.webhook_secret');
            
            // Verify webhook signature
            $signature = $request->header('X-Paystack-Signature');
            
            if (!$signature) {
                Log::warning('Paystack webhook: Missing signature header');
                return response()->json(['message' => 'Missing signature'], 400);
            }

            // Verify signature (Paystack sends HMAC SHA512 hash)
            $computedSignature = hash_hmac('sha512', $request->getContent(), $webhookSecret ?? $this->paystackSecretKey);
            
            if (!hash_equals($signature, $computedSignature)) {
                Log::warning('Paystack webhook: Invalid signature', [
                    'received' => substr($signature, 0, 20) . '...',
                    'computed' => substr($computedSignature, 0, 20) . '...'
                ]);
                return response()->json(['message' => 'Invalid signature'], 400);
            }

            $event = $request->json('event');
            $data = $request->json('data');

            Log::info('Paystack webhook received', [
                'event' => $event,
                'reference' => $data['reference'] ?? null
            ]);

            // Handle different webhook events
            switch ($event) {
                case 'charge.success':
                case 'transfer.success':
                    return $this->handleSuccessfulPayment($data);
                    
                case 'charge.failed':
                case 'transfer.failed':
                    return $this->handleFailedPayment($data);
                    
                default:
                    Log::info('Paystack webhook: Unhandled event', ['event' => $event]);
                    return response()->json(['message' => 'Event received'], 200);
            }

        } catch (\Exception $e) {
            Log::error('Paystack webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Always return 200 to prevent Paystack from retrying
            return response()->json(['message' => 'Webhook processed'], 200);
        }
    }

    /**
     * Handle successful payment webhook
     */
    private function handleSuccessfulPayment($data)
    {
        $reference = $data['reference'] ?? null;
        
        if (!$reference) {
            Log::warning('Paystack webhook: Missing reference in successful payment');
            return response()->json(['message' => 'Missing reference'], 200);
        }

        // Find donation by reference
        $donation = Donation::where('payment_reference', $reference)->first();
        
        if (!$donation) {
            Log::warning('Paystack webhook: Donation not found', ['reference' => $reference]);
            return response()->json(['message' => 'Donation not found'], 200);
        }

        // Load relationships
        $donation->load('donor', 'project');
        
        // Update donation status
        $donation->update([
            'status' => 'completed',
            'verified_at' => now(),
            'paid_at' => $data['paid_at'] ?? now()
        ]);

        // Update project raised amount if donation has project_id
        if ($donation->project_id) {
            $this->updateProjectRaised($donation->project_id, $donation->id);
        }

        // Send thank you email
        $this->sendThankYouEmail($donation);

        Log::info('Paystack webhook: Payment marked as completed', [
            'donation_id' => $donation->id,
            'reference' => $reference,
            'amount' => $data['amount'] ?? null
        ]);

        return response()->json(['message' => 'Payment processed successfully'], 200);
    }

    /**
     * Handle failed payment webhook
     */
    private function handleFailedPayment($data)
    {
        $reference = $data['reference'] ?? null;
        
        if (!$reference) {
            Log::warning('Paystack webhook: Missing reference in failed payment');
            return response()->json(['message' => 'Missing reference'], 200);
        }

        // Find donation by reference
        $donation = Donation::where('payment_reference', $reference)->first();
        
        if (!$donation) {
            Log::warning('Paystack webhook: Donation not found for failed payment', ['reference' => $reference]);
            return response()->json(['message' => 'Donation not found'], 200);
        }

        // Update donation status
        $donation->update([
            'status' => 'failed',
            'verified_at' => now()
        ]);

        Log::info('Paystack webhook: Payment marked as failed', [
            'donation_id' => $donation->id,
            'reference' => $reference,
            'gateway_response' => $data['gateway_response'] ?? null
        ]);

        return response()->json(['message' => 'Payment failure processed'], 200);
    }

    /**
     * Send thank you email to donor after successful payment
     */
    private function sendThankYouEmail($donation)
    {
        try {
            $donor = $donation->donor;
            
            if (!$donor || !$donor->email) {
                Log::warning('Cannot send thank you email: donor or email missing', [
                    'donation_id' => $donation->id,
                    'donor_id' => $donation->donor_id
                ]);
                return;
            }

            $donorName = $donor->full_name ?? trim("{$donor->surname} {$donor->name} {$donor->other_name}");
            $amount = number_format($donation->amount, 2);
            $reference = $donation->payment_reference;
            $projectName = $donation->project ? $donation->project->project_title : 'ABU Endowment Fund';
            
            Mail::send('emails.thank-you', [
                'donorName' => $donorName,
                'amount' => $amount,
                'reference' => $reference,
                'projectName' => $projectName,
                'donationDate' => $donation->paid_at ?? now(),
                'donationType' => $donation->endowment === 'yes' ? 'Endowment Fund' : 'Project Donation'
            ], function($message) use ($donor) {
                $message->from(config('mail.from.address', 'noreply@abu-endowment.edu.ng'), config('mail.from.name', 'ABU Endowment Fund'))
                        ->to($donor->email)
                        ->subject('Thank You for Your Generous Donation - ABU Endowment Fund');
            });

            Log::info('Thank you email sent successfully', [
                'donation_id' => $donation->id,
                'donor_email' => $donor->email
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send thank you email', [
                'donation_id' => $donation->id,
                'error' => $e->getMessage()
            ]);
            // Don't throw - email failure shouldn't break the payment flow
        }
    }

    /**
     * Test Paystack configuration
     */
    public function test()
    {
        $config = [
            'secret_key_set' => !empty($this->paystackSecretKey),
            'secret_key_length' => strlen($this->paystackSecretKey ?? ''),
            'secret_key_prefix' => substr($this->paystackSecretKey ?? '', 0, 7) . '...',
            'public_key_set' => !empty($this->paystackPublicKey),
            'public_key_length' => strlen($this->paystackPublicKey ?? ''),
        ];

        // Try a test API call to verify the key works
        $testResponse = null;
        if (!empty($this->paystackSecretKey)) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->paystackSecretKey,
                ])->get('https://api.paystack.co/bank');
                
                $testResponse = [
                    'status' => $response->status(),
                    'success' => $response->successful(),
                    'message' => $response->successful() ? 'API key is valid' : ($response->json()['message'] ?? 'API call failed')
                ];
            } catch (\Exception $e) {
                $testResponse = [
                    'error' => $e->getMessage()
                ];
            }
        }

        return response()->json([
            'success' => !empty($this->paystackSecretKey),
            'config' => $config,
            'api_test' => $testResponse,
            'message' => empty($this->paystackSecretKey) 
                ? 'PAYSTACK_SECRET_KEY is not set in .env file'
                : 'Configuration check complete'
        ]);
    }

    /**
     * Convert Naira to Kobo
     */
    private function nairaToKobo($naira)
    {
        return intval($naira * 100);
    }

    /**
     * Find or create donor based on authenticated donor_id, device session, or email
     */
    private function findOrCreateDonor($deviceFingerprint, $metadata, $email)
    {
        // Priority 1: Use authenticated donor_id from metadata (if provided)
        if (!empty($metadata['donor_id'])) {
            $donor = Donor::find($metadata['donor_id']);
            if ($donor) {
                // Update donor info from metadata (but preserve email to avoid conflicts)
                $donor->name = $metadata['name'] ?? $donor->name;
                $donor->surname = $metadata['surname'] ?? $donor->surname;
                $donor->other_name = $metadata['other_name'] ?? $donor->other_name;
                // Only update phone if provided and not empty
                if (isset($metadata['phone']) && !empty($metadata['phone'])) {
                    $donor->phone = $metadata['phone'];
                }
                // Don't update email - use authenticated user's existing email
                $donor->save();
                return $donor;
            }
        }

        // Priority 2: Try to find existing donor by device session (if device_fingerprint provided)
        if (!empty($deviceFingerprint)) {
            $deviceSession = DeviceSession::where('device_fingerprint', $deviceFingerprint)->first();
            
            if ($deviceSession && $deviceSession->donor) {
                // Update existing donor info if needed - SAVE SEPARATE NAME FIELDS
                $donor = $deviceSession->donor;
                $donor->name = $metadata['name'] ?? $donor->name;
                $donor->surname = $metadata['surname'] ?? $donor->surname;
                $donor->other_name = $metadata['other_name'] ?? $donor->other_name;
                
                // Only update email if provided, different, and doesn't conflict with another donor
                if (!empty($email) && $email !== $donor->email) {
                    $emailExists = Donor::where('email', $email)
                                       ->where('id', '!=', $donor->id)
                                       ->exists();
                    
                    if (!$emailExists) {
                        // Safe to update - email doesn't belong to another donor
                        $donor->email = $email;
                    } else {
                        // Email belongs to another donor - don't update, log warning
                        Log::warning("Cannot update donor {$donor->id} email: {$email} already exists for another donor", [
                            'donor_id' => $donor->id,
                            'current_email' => $donor->email,
                            'requested_email' => $email
                        ]);
                    }
                }
                
                // Only update phone if provided and not empty
                if (isset($metadata['phone']) && !empty($metadata['phone'])) {
                    $donor->phone = $metadata['phone'];
                }
                $donor->save();
                return $donor;
            }
        }

        // Priority 3: Try to find donor by email if provided
        if (!empty($email)) {
            $donor = Donor::where('email', $email)->first();
            if ($donor) {
                // Update existing donor - SAVE SEPARATE NAME FIELDS
                $donor->name = $metadata['name'] ?? $donor->name;
                $donor->surname = $metadata['surname'] ?? $donor->surname;
                $donor->other_name = $metadata['other_name'] ?? $donor->other_name;
                // Email already matches (we found donor by this email), no need to update
                // Only update phone if provided and not empty
                if (isset($metadata['phone']) && !empty($metadata['phone'])) {
                    $donor->phone = $metadata['phone'];
                }
                $donor->save();
                return $donor;
            }
        }

        // Create new donor - SAVE SEPARATE NAME FIELDS
        // Note: We've already checked for existing donors by device session and email above
        return Donor::create([
            'name' => $metadata['name'] ?? '',
            'surname' => $metadata['surname'] ?? '',
            'other_name' => $metadata['other_name'] ?? null,
            'email' => $email, // Use email from request (we've verified it doesn't exist)
            'phone' => !empty($metadata['phone']) ? $metadata['phone'] : null, // Allow null phone
            'donor_type' => 'addressable_alumni', // Default type
        ]);
    }

    /**
     * Create or update device session
     */
    private function createDeviceSession($donor, $deviceFingerprint, $request)
    {
        // Find existing session or create new one
        $deviceSession = DeviceSession::firstOrNew(
            ['device_fingerprint' => $deviceFingerprint]
        );
        
        // Set common fields
        $deviceSession->donor_id = $donor->id;
        $deviceSession->ip_address = $request->ip();
        $deviceSession->user_agent = $request->userAgent();
        $deviceSession->expires_at = now()->addDays(30); // Extend/Set session expiry
        
        // Only generate token if creating new session
        if (!$deviceSession->exists || !$deviceSession->session_token) {
            $deviceSession->session_token = bin2hex(random_bytes(32)); // 64 character token
        }
        
        $deviceSession->save();
    }

    /**
     * Update project raised amount by summing all completed donations
     */
    /**
     * Update the raised amount for a project
     * 
     * This method calculates the total raised from all completed donations
     * for the project and updates the project's raised column.
     * 
     * @param int $projectId The project ID to update
     * @param int|null $donationId Optional donation ID for logging
     * @return bool Success status (true if updated, false otherwise)
     */
    protected function updateProjectRaised($projectId, $donationId = null)
    {
        try {
            // Find project (including soft-deleted check)
            $project = Project::find($projectId);
            
            if (!$project) {
                Log::warning('Cannot update project raised: Project not found', [
                    'project_id' => $projectId,
                    'donation_id' => $donationId
                ]);
                return false;
            }

            // Check if project is soft-deleted
            if ($project->trashed()) {
                Log::warning('Cannot update project raised: Project is soft-deleted', [
                    'project_id' => $projectId,
                    'project_title' => $project->project_title,
                    'donation_id' => $donationId
                ]);
                return false;
            }

            // Store old raised amount for logging
            $oldRaised = $project->raised ?? 0;

            // Calculate total raised from all completed donations for this project
            // Using database transaction to ensure consistency
            $totalRaised = DB::transaction(function () use ($projectId) {
                return Donation::where('project_id', $projectId)
                    ->where('status', 'completed')
                    ->lockForUpdate() // Prevent race conditions with concurrent payments
                    ->sum('amount');
            });

            // Ensure totalRaised is numeric (handle null case)
            $totalRaised = $totalRaised ?? 0;

            // Update project raised column within transaction
            DB::transaction(function () use ($project, $totalRaised) {
                $project->update(['raised' => $totalRaised]);
            });

            Log::info('Project raised amount updated successfully', [
                'project_id' => $projectId,
                'project_title' => $project->project_title,
                'old_raised' => $oldRaised,
                'new_raised' => $totalRaised,
                'difference' => $totalRaised - $oldRaised,
                'donation_id' => $donationId
            ]);

            return true;

        } catch (\Exception $e) {
            // Log error but don't throw - allow payment processing to continue
            Log::error('Failed to update project raised amount', [
                'project_id' => $projectId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'donation_id' => $donationId
            ]);

            // Return false to indicate failure, but don't throw exception
            // This ensures payment processing continues even if project update fails
            return false;
        }
    }
}