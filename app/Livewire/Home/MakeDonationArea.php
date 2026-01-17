<?php

namespace App\Livewire\Home;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Donation;
use App\Models\DonorSession;
use App\Models\Donor;

class MakeDonationArea extends Component
{
    public $amount;
    
    // User details
    public $email;
    public $name;
    public $phone;
    
    protected $listeners = ['payment-success' => 'verifyPayment'];

    public function mount()
    {
        $this->checkAuth();
    }

    public function checkAuth()
    {
        if (Session::has('donor_token')) {
            $token = Session::get('donor_token');
            $donorSession = DonorSession::with('donor')->find($token);
            
            if ($donorSession && $donorSession->donor) {
                $this->email = $donorSession->donor->email;
                $this->name = $donorSession->donor->name; // Or full name
                $this->phone = $donorSession->donor->phone;
            } elseif ($donorSession) {
                $this->email = $donorSession->username;
            }
        }
    }

    public function donate()
    {
        $this->validate([
            'amount' => 'required|numeric|min:100',
            'email' => 'required|email',
        ]);

        // Create pending donation
        $reference = 'ABU_' . time() . '_' . uniqid();
        
        // Find or create donor if not logged in (simplified logic for now)
        $donorId = null;
        if (Session::has('donor_token')) {
             $token = Session::get('donor_token');
             $session = DonorSession::find($token);
             $donorId = $session ? $session->donor_id : null;
        }

        if (!$donorId) {
             // Try to find by email or create basic donor
             $donor = Donor::firstOrCreate(
                 ['email' => $this->email],
                 ['name' => 'Guest', 'surname' => 'Donor', 'donor_type' => 'supporter']
             );
             $donorId = $donor->id;
        }

        $donation = Donation::create([
            'donor_id' => $donorId,
            'amount' => $this->amount,
            'type' => 'endowment', // Defaulting to endowment for general donations
            'frequency' => 'onetime',
            'endowment' => 'yes',
            'status' => 'pending',
            'payment_reference' => $reference,
        ]);

        // Dispatch event to frontend to open Paystack
        $this->dispatch('initiate-paystack', [
            'key' => config('services.paystack.public_key'),
            'email' => $this->email,
            'amount' => $this->amount * 100, // In kobo
            'ref' => $reference,
            'currency' => 'NGN',
            'metadata' => [
                'donation_id' => $donation->id,
                'custom_fields' => [
                    [
                        'display_name' => "Donation Type",
                        'variable_name' => "donation_type",
                        'value' => "Endowment"
                    ]
                ]
            ]
        ]);
    }

    public function verifyPayment($data = null)
    {
        if (!$data) {
            return;
        }

        // Verify with Paystack via backend API or direct check
        // For simplicity and security, we'll verify here using the secret key
        $reference = is_array($data) ? $data['reference'] : $data;
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
            ])->get("https://api.paystack.co/transaction/verify/{$reference}");

            if ($response->successful()) {
                $data = $response->json()['data'];
                
                if ($data['status'] === 'success') {
                    $donation = Donation::where('payment_reference', $reference)->first();
                    
                    if ($donation) {
                        $donation->update([
                            'status' => 'completed',
                            'verified_at' => now(),
                            'paid_at' => now(),
                        ]);
                        
                        session()->flash('message', 'Donation successful! Thank you for your support.');
                        $this->dispatch('donation-completed');
                    }
                } else {
                    session()->flash('error', 'Payment verification failed.');
                }
            }
        } catch (\Exception $e) {
            Log::error('Payment verification error: ' . $e->getMessage());
            session()->flash('error', 'An error occurred during verification.');
        }
    }

    public function render()
    {
        return view('livewire.home.make-donation-area');
    }
}
