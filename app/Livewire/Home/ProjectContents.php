<?php

namespace App\Livewire\Home;

use Livewire\Component;
use App\Models\Project;
use App\Models\Donation;
use Illuminate\Support\Facades\Session;
use App\Models\DonorSession;
use App\Models\Donor;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProjectContents extends Component
{
    public $project;
    public $otherProjects;
    
    // Donation properties
    public $showModal = false;
    public $amount = 1000;
    public $customAmount;
    public $selectedAmount = 1000;
    public $email;
    public $name;
    public $phone;
    
    // Gallery properties
    public $showImageGallery = false;
    public $galleryProject = null;

    protected $listeners = ['project-payment-success' => 'verifyPayment'];

    public function mount($project)
    {
        $this->project = $project;
        $this->loadOtherProjects();
        $this->checkAuth();
    }
    
    public function loadOtherProjects()
    {
        $this->otherProjects = Project::where('id', '!=', $this->project->id)
                                      ->take(3)
                                      ->get();
    }

    public function checkAuth()
    {
        if (Session::has('donor_token')) {
            $token = Session::get('donor_token');
            $donorSession = DonorSession::with('donor')->find($token);
            
            if ($donorSession && $donorSession->donor) {
                $this->email = $donorSession->donor->email;
                $this->name = $donorSession->donor->name;
                $this->phone = $donorSession->donor->phone;
            } elseif ($donorSession) {
                $this->email = $donorSession->username;
            }
        }
    }

    public function openDonationModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['amount', 'customAmount', 'selectedAmount']);
    }

    public function updatedSelectedAmount($value)
    {
        if ($value === 'custom') {
            $this->amount = $this->customAmount;
        } else {
            $this->amount = $value;
            $this->customAmount = null;
        }
    }

    public function updatedCustomAmount($value)
    {
        $this->selectedAmount = 'custom';
        $this->amount = $value;
    }

    public function donate()
    {
        $this->validate([
            'amount' => 'required|numeric|min:100',
            'email' => 'required|email',
        ]);

        // Create pending donation
        $reference = 'ABU_PRJ_' . time() . '_' . uniqid();
        
        // Find or create donor
        $donorId = null;
        if (Session::has('donor_token')) {
            $token = Session::get('donor_token');
            $session = DonorSession::find($token);
            $donorId = $session ? $session->donor_id : null;
        }

        if (!$donorId) {
            $donor = Donor::firstOrCreate(
                ['email' => $this->email],
                ['name' => $this->name ?? 'Guest', 'surname' => 'Donor', 'donor_type' => 'supporter']
            );
            $donorId = $donor->id;
        }

        $donation = Donation::create([
            'donor_id' => $donorId,
            'project_id' => $this->project->id,
            'amount' => $this->amount,
            'type' => 'project',
            'frequency' => 'onetime',
            'endowment' => 'no',
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
                'project_id' => $this->project->id,
                'project_name' => $this->project->project_title,
                'custom_fields' => [
                    [
                        'display_name' => "Project",
                        'variable_name' => "project_name",
                        'value' => $this->project->project_title
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

        $reference = is_array($data) ? $data['reference'] : $data;
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
            ])->get("https://api.paystack.co/transaction/verify/{$reference}");

            if ($response->successful()) {
                $paymentData = $response->json()['data'];
                
                if ($paymentData['status'] === 'success') {
                    $donation = Donation::where('payment_reference', $reference)->first();
                    
                    if ($donation && $donation->status !== 'completed') {
                        $donation->update([
                            'status' => 'completed',
                            'verified_at' => now(),
                            'paid_at' => now(),
                        ]);

                        // Update Project Raised Amount
                        $totalRaised = Donation::where('project_id', $this->project->id)
                                               ->whereIn('status', ['success', 'paid', 'completed'])
                                               ->sum('amount');
                        $this->project->update(['raised' => $totalRaised]);
                        
                        // Close the modal
                        $this->showModal = false;
                        
                        // Flash success message for toast
                        session()->flash('message', 'Thank you for your donation! Your support means the world to us.');
                        
                        // Refresh page to show updated raised amount
                        return redirect()->route('project.single', ['id' => $this->project->id]);
                    }
                } else {
                    $this->dispatch('show-toast', [
                        'type' => 'error',
                        'message' => 'Payment verification failed. Please contact support.'
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Payment verification error: ' . $e->getMessage());
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'An error occurred during verification.'
            ]);
        }
    }
    
    public function openImageGallery($projectId)
    {
        // For other projects section
        $this->galleryProject = Project::with('photos')->find($projectId);
        $this->showImageGallery = true;
    }

    public function closeImageGallery()
    {
        $this->showImageGallery = false;
        $this->galleryProject = null;
    }

    public function render()
    {
        return view('livewire.home.project-contents');
    }
}
