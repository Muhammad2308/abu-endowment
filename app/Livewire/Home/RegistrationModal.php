<?php

namespace App\Livewire\Home;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Session;

class RegistrationModal extends Component
{
    // ... properties ...
    public $show = false;
    public $donorType = '';
    public $surname = '';
    public $name = '';
    public $otherName = '';
    public $email = '';
    public $phone = '';
    public $state = '';
    public $lga = '';
    public $nationality = 'Nigerian';
    public $username = '';
    public $password = '';
    public $passwordConfirm = '';
    
    // Alumni fields
    public $regNumber = '';
    public $entryYear = '';
    public $graduationYear = '';
    
    public $error = '';
    public $success = '';
    public $loading = false;
    public $showAlumniFields = false;

    protected $listeners = [
        'openRegistrationModal' => 'open',
        'save-auth-token' => 'saveAuthToken'
    ];

    public function updatedDonorType()
    {
        $this->showAlumniFields = in_array($this->donorType, ['addressable_alumni', 'non_addressable_alumni']);
    }

    public function open()
    {
        $this->show = true;
        $this->reset([
            'donorType', 'surname', 'name', 'otherName', 'email', 'phone',
            'state', 'lga', 'nationality', 'username', 'password', 'passwordConfirm',
            'regNumber', 'entryYear', 'graduationYear', 'error', 'success', 'loading', 'showAlumniFields'
        ]);
        $this->nationality = 'Nigerian';
        
        // Initialize Google Sign-In button when modal opens
        $this->dispatch('initGoogleRegistration', componentId: 'register');
    }

    public function close()
    {
        $this->show = false;
        $this->reset([
            'donorType', 'surname', 'name', 'otherName', 'email', 'phone',
            'state', 'lga', 'nationality', 'username', 'password', 'passwordConfirm',
            'regNumber', 'entryYear', 'graduationYear', 'error', 'success', 'loading', 'showAlumniFields'
        ]);
    }

    public function register()
    {
        $this->error = '';
        $this->success = '';
        $this->loading = true;

        $this->validate([
            'donorType' => 'required|string',
            'surname' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'state' => 'required|string',
            'lga' => 'required|string',
            'nationality' => 'required|string',
            'username' => 'required|string|min:3',
            'password' => 'required|string|min:6',
            'passwordConfirm' => 'required|string|same:password',
        ]);

        if ($this->password !== $this->passwordConfirm) {
            $this->error = 'Passwords do not match!';
            $this->loading = false;
            return;
        }

        try {
            // Step 1: Create donor
            $donorData = [
                'surname' => $this->surname,
                'name' => $this->name,
                'other_name' => $this->otherName ?: null,
                'email' => $this->email,
                'phone' => $this->phone,
                'state' => $this->state,
                'lga' => $this->lga,
                'nationality' => $this->nationality,
                'donor_type' => $this->donorType,
            ];

            if ($this->showAlumniFields) {
                $donorData['reg_number'] = $this->regNumber;
                $donorData['entry_year'] = $this->entryYear ? (int)$this->entryYear : null;
                $donorData['graduation_year'] = $this->graduationYear ? (int)$this->graduationYear : null;
            }

            $donorResponse = Http::withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
            ])->post(url('/api/donors'), $donorData);

            $donorResult = $donorResponse->json();

            if (!$donorResponse->successful()) {
                throw new \Exception($donorResult['message'] ?? json_encode($donorResult['errors'] ?? []) ?? 'Registration failed');
            }

            // Step 2: Create donor session
            $sessionResponse = Http::withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
            ])->post(url('/api/donor-sessions/register'), [
                'username' => $this->username,
                'password' => $this->password,
                'donor_id' => $donorResult['data']['id'],
            ]);

            $sessionResult = $sessionResponse->json();

            if (!$sessionResponse->successful()) {
                throw new \Exception($sessionResult['message'] ?? 'Failed to create session');
            }

            // Store token in session
            if (isset($sessionResult['token'])) {
                Session::put('donor_token', $sessionResult['token']);
            }

            // Success
            $this->success = 'Registration successful! Logging you in...';
            $this->close();
            $this->dispatch('registration-success');
            $this->js('setTimeout(() => window.location.reload(), 1500)');
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        } finally {
            $this->loading = false;
        }
    }

    public function saveAuthToken($token)
    {
        if ($token) {
            Session::put('donor_token', $token);
            $this->close();
            $this->dispatch('login-success');
            $this->js('window.location.reload()');
        }
    }

    public function render()
    {
        return view('livewire.home.registration-modal');
    }
}

