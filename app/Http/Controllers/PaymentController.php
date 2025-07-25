<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
            $request->validate([
                'email' => 'required|email',
                'amount' => 'required|numeric|min:1', // Minimum 1 naira
                'metadata' => 'required|array',
                'metadata.name' => 'required|string',
                'metadata.phone' => 'required|string',
                'metadata.endowment' => 'required|in:yes,no',
                'metadata.project_id' => 'nullable|exists:projects,id',
                'device_fingerprint' => 'required|string',
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

            // Find or create donor based on device session
            $donor = $this->findOrCreateDonor($deviceFingerprint, $metadata);

            $amountNaira = $request->amount; // e.g., 1000
            $amountKobo = $this->nairaToKobo($amountNaira);

            // 1. Create donation record FIRST (store naira)
            $donation = Donation::create([
                'donor_id' => $donor->id,
                'project_id' => $metadata['project_id'] ?? null,
                'amount' => $amountNaira, // store in naira
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
                    'device_fingerprint' => $deviceFingerprint,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // 3. Update donation with Paystack reference
                $donation->update([
                    'payment_reference' => $data['data']['reference']
                ]);

                // Create/Update device session
                $this->createDeviceSession($donor, $deviceFingerprint, $request);

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
                
                Log::error('Paystack initialization failed', $response->json());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to initialize payment: ' . ($response->json()['message'] ?? 'Unknown error')
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Payment initialization error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while initializing payment: ' . $e->getMessage()
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
                    'success' => false,
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

                    Log::info('Payment verified successfully', [
                        'donation_id' => $donation->id,
                        'reference' => $reference,
                        'amount' => $donation->amount
                    ]);

                    // Convert Paystack kobo amount to naira for API response
                    $transaction['amount'] = $this->koboToNaira($transaction['amount']);

                    return response()->json([
                        'success' => true,
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

                    // Convert Paystack kobo amount to naira for API response
                    $transaction['amount'] = $this->koboToNaira($transaction['amount']);

                    return response()->json([
                        'success' => false,
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
                    'success' => false,
                    'message' => 'Failed to verify payment'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Payment verification error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while verifying payment: ' . $e->getMessage()
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

                    Log::info('Payment webhook processed successfully', [
                        'donation_id' => $donation->id,
                        'reference' => $reference,
                        'amount' => $donation->amount
                    ]);
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Webhook processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Find or create donor based on device session
     */
    private function findOrCreateDonor($deviceFingerprint, $metadata)
    {
        // First, try to find existing device session
        $deviceSession = DeviceSession::where('device_fingerprint', $deviceFingerprint)
                                     ->where('expires_at', '>', now())
                                     ->first();

        if ($deviceSession && $deviceSession->donor) {
            return $deviceSession->donor;
        }

        // If no session, check if donor exists by email/phone
        $donor = null;
        if (isset($metadata['email'])) {
            $donor = Donor::where('email', $metadata['email'])->first();
        }
        
        if (!$donor && isset($metadata['phone'])) {
            $donor = Donor::where('phone', $metadata['phone'])->first();
        }

        // If donor exists, return it (session will be created later)
        if ($donor) {
            return $donor;
        }

        // If no donor exists, create new one
        $donor = Donor::create([
            'name' => $metadata['name'],
            'surname' => '',
            'other_name' => '',
            'email' => $metadata['email'] ?? null,
            'phone' => $metadata['phone'],
            'address' => $metadata['address'] ?? '',
            'state' => $metadata['state'] ?? '',
            'lga' => $metadata['city'] ?? '',
            'country' => $metadata['country'] ?? 'Nigeria',
            'donor_type' => 'non_addressable_alumni',
            'nationality' => $metadata['country'] ?? 'Nigeria',
        ]);

        Log::info('New donor created during payment', [
            'donor_id' => $donor->id,
            'name' => $donor->name,
            'email' => $donor->email
        ]);

        return $donor;
    }

    /**
     * Create device session for donor
     */
    private function createDeviceSession($donor, $deviceFingerprint, $request)
    {
        // Check if device session already exists
        $deviceSession = DeviceSession::where('device_fingerprint', $deviceFingerprint)->first();

        if ($deviceSession) {
            // Update existing session
            $deviceSession->update([
                'donor_id' => $donor->id,
                'expires_at' => now()->addDays(30),
                'user_agent' => $request->header('User-Agent'),
                'ip_address' => $request->ip()
            ]);
        } else {
            // Create new session
            DeviceSession::create([
                'donor_id' => $donor->id,
                'device_fingerprint' => $deviceFingerprint,
                'session_token' => \Illuminate\Support\Str::random(60),
                'user_agent' => $request->header('User-Agent'),
                'ip_address' => $request->ip(),
                'expires_at' => now()->addDays(30)
            ]);
        }
    }

    /**
     * Convert kobo to naira
     */
    private function koboToNaira($amountKobo)
    {
        return $amountKobo / 100;
    }

    /**
     * Convert naira to kobo
     */
    private function nairaToKobo($amountNaira)
    {
        return $amountNaira * 100;
    }

    /**
     * Test endpoint to verify configuration
     */
    public function test(Request $request)
    {
        try {
            $config = [
                'paystack_configured' => !empty($this->paystackSecretKey) && !empty($this->paystackPublicKey),
                'paystack_secret_key_length' => strlen($this->paystackSecretKey ?? ''),
                'paystack_public_key_length' => strlen($this->paystackPublicKey ?? ''),
                'database_connected' => true,
                'donations_table_exists' => \Illuminate\Support\Facades\Schema::hasTable('donations'),
                'device_sessions_table_exists' => \Illuminate\Support\Facades\Schema::hasTable('device_sessions'),
            ];

            // Test Paystack connection
            if ($config['paystack_configured']) {
                try {
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $this->paystackSecretKey,
                    ])->get('https://api.paystack.co/transaction/totals');
                    
                    $config['paystack_connection'] = $response->successful();
                    $config['paystack_response'] = $response->json();
                } catch (\Exception $e) {
                    $config['paystack_connection'] = false;
                    $config['paystack_error'] = $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Configuration test completed',
                'config' => $config
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Configuration test failed: ' . $e->getMessage(),
                'config' => [
                    'paystack_configured' => !empty($this->paystackSecretKey) && !empty($this->paystackPublicKey),
                    'database_connected' => false,
                    'error' => $e->getMessage()
                ]
            ], 500);
        }
    }
} 