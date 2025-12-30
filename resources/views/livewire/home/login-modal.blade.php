<div>
    @if($show)
    <!-- Modal Backdrop -->
    <div class="modal-backdrop fade show" 
         wire:click="close"
         style="z-index: 9998;"></div>

    <!-- Modal Container -->
    <div class="modal fade show d-block" 
         tabindex="-1" 
         role="dialog" 
         style="z-index: 9999; overflow-y: auto;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0 rounded-lg">
                
                <!-- Modal Header -->
                <div class="modal-header text-white border-0"
                     style="background: linear-gradient(135deg, #3CC78F 0%, #2ecc71 100%); border-radius: 0.5rem 0.5rem 0 0; padding: 1.5rem;">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fa fa-sign-in-alt mr-2"></i> Login to Your Account
                    </h5>
                    <button type="button" 
                            class="close text-white opacity-100" 
                            wire:click="close"
                            aria-label="Close"
                            style="text-shadow: none; opacity: 1;">
                        <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body p-4 p-md-5">
                    <form wire:submit.prevent="login">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-dark small text-uppercase mb-2">Username *</label>
                            <input type="text" 
                                   wire:model="username"
                                   class="form-control form-control-lg bg-light border-0"
                                   style="border-radius: 8px; font-size: 0.95rem;"
                                   required>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-dark small text-uppercase mb-2">Password *</label>
                            <input type="password" 
                                   wire:model="password"
                                   class="form-control form-control-lg bg-light border-0"
                                   style="border-radius: 8px; font-size: 0.95rem;"
                                   required>
                        </div>

                        @if($error)
                        <div class="alert alert-danger rounded-lg border-0 mb-4">
                            {{ $error }}
                        </div>
                        @endif

                        <div id="googleBtnLogin-login" class="mb-4" wire:ignore.self></div>

                        <div class="text-center mb-4 position-relative">
                            <hr class="my-0">
                            <span class="position-absolute bg-white px-3 text-muted" style="top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 0.9rem;">OR</span>
                        </div>

                        <button type="submit" 
                                class="btn btn-primary btn-lg btn-block font-weight-bold shadow-sm"
                                style="background: #3CC78F; border: none; border-radius: 8px; padding: 12px;"
                                @if($loading) disabled @endif>
                            @if(!$loading)
                                Login
                            @else
                                <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>
                                Logging in...
                            @endif
                        </button>

                        <p class="text-center mt-4 mb-0 text-muted">
                            Don't have an account? 
                            <a href="#" 
                               class="text-success font-weight-bold text-decoration-none"
                               wire:click.prevent="close"
                               onclick="setTimeout(() => Livewire.dispatch('openRegistrationModal'), 100);">
                                Register here
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script src="https://accounts.google.com/gsi/client" async defer></script>
    @script
    <script>
        // Make functions globally accessible
        window.initGoogleLoginButton = function() {
            const buttonId = 'googleBtnLogin-login';
            const buttonElement = document.getElementById(buttonId);
            
            if (!buttonElement) {
                console.warn('Google button element not found:', buttonId);
                return;
            }

            // Wait for Google script to load
            let retries = 0;
            function tryInit() {
                if (window.google && window.google.accounts && window.google.accounts.id) {
                    console.log('Initializing Google Sign-In button');
                    // Clear any existing button
                    buttonElement.innerHTML = '';
                    
                    // Get button width from parent
                    const parentWidth = buttonElement.parentElement.offsetWidth || 400;
                    
                    // Initialize Google Sign-In
                    window.google.accounts.id.initialize({
                        client_id: "{{ config('services.google.client_id') }}",
                        callback: window.handleGoogleCredentialResponse
                    });
                    
                    // Render Google Sign-In button with pixel width
                    window.google.accounts.id.renderButton(
                        buttonElement,
                        { 
                            theme: "outline", 
                            size: "large", 
                            width: parentWidth - 20, // Use pixel width instead of percentage
                            text: "signin_with" 
                        }
                    );
                    console.log('Google Sign-In button rendered');
                } else {
                    retries++;
                    if (retries < 50) { // Try for up to 5 seconds
                        setTimeout(tryInit, 100);
                    } else {
                        console.error('Google Sign-In script not loaded after 5 seconds');
                    }
                }
            }
            
            tryInit();
        };

        // Handle Google credential response - make it global
        window.handleGoogleCredentialResponse = function(response) {
            console.log('Google credential received, processing...');
            
            // Use fetch to avoid server-side deadlock
            fetch('/api/donor-sessions/google-login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({ 
                    token: response.credential,
                    device_session_id: null 
                })
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(data => {
                        throw new Error(data.message || 'Google login failed');
                    });
                }
                return res.json();
            })
            .then(data => {
                console.log('Google Login API Response:', data);
                
                // Handle different response structures
                let token = data.token;
                if (!token && data.data && data.data.token) {
                    token = data.data.token;
                }
                
                if (token) {
                    console.log('Google login successful, saving session...');
                    Livewire.dispatch('save-auth-token', { token: token });
                } else {
                    console.error('Token missing in response:', data);
                    throw new Error('No token received from server');
                }
            })
            .catch(error => {
                console.error('Google login error:', error);
                alert(error.message || 'Google login failed');
            });
        };

        // Watch for modal show state and initialize button
        $wire.watch('show', (value) => {
            if (value) {
                console.log('Login modal opened, initializing Google button');
                setTimeout(() => window.initGoogleLoginButton(), 300);
            }
        });

        // Also listen for the event
        Livewire.on('initGoogleLogin', () => {
            console.log('Received initGoogleLogin event');
            setTimeout(() => window.initGoogleLoginButton(), 100);
        });

        // Initialize on component mount if modal is already shown
        if ($wire.show) {
            setTimeout(() => window.initGoogleLoginButton(), 500);
        }
    </script>
    @endscript
</div>
