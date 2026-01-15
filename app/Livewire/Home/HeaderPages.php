<?php

namespace App\Livewire\Home;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\DonorSession;
use Illuminate\Support\Facades\Log;

class HeaderPages extends Component
{
    public $user = null;
    public $isLoggedIn = false;

    protected $listeners = [
        'login-success' => 'checkAuth',
        'registration-success' => 'checkAuth',
        'profile-updated' => 'checkAuth'
    ];

    public function mount()
    {
        $this->checkAuth();
    }

    public function checkAuth()
    {
        try {
            // Check session first
            if (Session::has('donor_token')) {
                $token = Session::get('donor_token');
                Log::info('HeaderPages: Found token in session', ['token' => $token]);
                
                // Use direct DB query instead of HTTP request to avoid self-request timeout
                $donorSession = DonorSession::with('donor')->find($token);
                
                if ($donorSession) {
                    $this->user = [
                        'username' => $donorSession->username,
                        'name' => $donorSession->donor->name ?? $donorSession->username,
                        'email' => $donorSession->donor->email ?? $donorSession->username,
                        'avatar' => $donorSession->donor->profile_image ?? null,
                    ];
                    
                    $this->isLoggedIn = true;
                    Log::info('HeaderPages: Auth successful', ['user' => $this->user]);
                    return;
                } else {
                    Log::info('HeaderPages: Session not found in DB', ['id' => $token]);
                }
            } else {
                Log::info('HeaderPages: No token in session');
            }
            
            $this->user = null;
            $this->isLoggedIn = false;
        } catch (\Exception $e) {
            Log::error('HeaderPages: Error checking auth', ['error' => $e->getMessage()]);
            $this->user = null;
            $this->isLoggedIn = false;
        }
    }

    public function logout()
    {
        try {
            if (Session::has('donor_token')) {
                // Optional: Call API to invalidate token if needed, but for now just clear session
                // Http::withToken(Session::get('donor_token'))->post(url('/api/donor-sessions/logout'));
                
                Session::forget('donor_token');
            }
            
            $this->user = null;
            $this->isLoggedIn = false;
            $this->dispatch('logout-success');
            
            return redirect('/');
        } catch (\Exception $e) {
            // Force logout on error
            Session::forget('donor_token');
            $this->user = null;
            $this->isLoggedIn = false;
            return redirect('/');
        }
    }

    public function render()
    {
        return view('livewire.home.header-pages');
    }
}
