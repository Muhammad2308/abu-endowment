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
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content shadow-lg border-0 rounded-lg">
                
                <!-- Modal Header -->
                <div class="modal-header text-white border-0"
                     style="background: linear-gradient(135deg, #3CC78F 0%, #2ecc71 100%); border-radius: 0.5rem 0.5rem 0 0; padding: 1.5rem;">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fa fa-user-plus mr-2"></i> Create Your Account
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
                    <form wire:submit.prevent="register">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-dark small text-uppercase mb-2">I am a: *</label>
                            <select wire:model="donorType" 
                                    class="form-control form-control-lg bg-light border-0"
                                    style="border-radius: 8px; font-size: 0.95rem;"
                                    required>
                                <option value="">Select your type</option>
                                <option value="addressable_alumni">Alumni</option>
                                <option value="supporter">Supporter</option>
                                <option value="non_addressable_alumni">Other</option>
                            </select>
                            <small class="text-muted mt-2 d-block">Select Alumni if you graduated from ABU, Supporter if you're a friend/well-wisher</small>
                        </div>

                        @if($showAlumniFields)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark small text-uppercase mb-2">Registration Number *</label>
                                    <input type="text" 
                                           wire:model="regNumber"
                                           placeholder="e.g., U16/ENG/1234"
                                           class="form-control form-control-lg bg-light border-0"
                                           style="border-radius: 8px; font-size: 0.95rem;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark small text-uppercase mb-2">Entry Year</label>
                                    <input type="number" 
                                           wire:model="entryYear"
                                           min="1950" 
                                           max="{{ date('Y') }}"
                                           placeholder="e.g., 2016"
                                           class="form-control form-control-lg bg-light border-0"
                                           style="border-radius: 8px; font-size: 0.95rem;">
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark small text-uppercase mb-2">Surname *</label>
                                    <input type="text" 
                                           wire:model="surname"
                                           class="form-control form-control-lg bg-light border-0"
                                           style="border-radius: 8px; font-size: 0.95rem;"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark small text-uppercase mb-2">First Name *</label>
                                    <input type="text" 
                                           wire:model="name"
                                           class="form-control form-control-lg bg-light border-0"
                                           style="border-radius: 8px; font-size: 0.95rem;"
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-dark small text-uppercase mb-2">Other Name (Optional)</label>
                            <input type="text" 
                                   wire:model="otherName"
                                   class="form-control form-control-lg bg-light border-0"
                                   style="border-radius: 8px; font-size: 0.95rem;">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark small text-uppercase mb-2">Email *</label>
                                    <input type="email" 
                                           wire:model="email"
                                           class="form-control form-control-lg bg-light border-0"
                                           style="border-radius: 8px; font-size: 0.95rem;"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark small text-uppercase mb-2">Phone Number *</label>
                                    <input type="tel" 
                                           wire:model="phone"
                                           class="form-control form-control-lg bg-light border-0"
                                           style="border-radius: 8px; font-size: 0.95rem;"
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark small text-uppercase mb-2">State *</label>
                                    <input type="text" 
                                           wire:model="state"
                                           class="form-control form-control-lg bg-light border-0"
                                           style="border-radius: 8px; font-size: 0.95rem;"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark small text-uppercase mb-2">LGA *</label>
                                    <input type="text" 
                                           wire:model="lga"
                                           class="form-control form-control-lg bg-light border-0"
                                           style="border-radius: 8px; font-size: 0.95rem;"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark small text-uppercase mb-2">Nationality *</label>
                                    <input type="text" 
                                           wire:model="nationality"
                                           class="form-control form-control-lg bg-light border-0"
                                           style="border-radius: 8px; font-size: 0.95rem;"
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-dark small text-uppercase mb-2">Username *</label>
                            <input type="text" 
                                   wire:model="username"
                                   minlength="3"
                                   class="form-control form-control-lg bg-light border-0"
                                   style="border-radius: 8px; font-size: 0.95rem;"
                                   required>
                            <small class="text-muted mt-2 d-block">Choose a unique username for login</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark small text-uppercase mb-2">Password *</label>
                                    <input type="password" 
                                           wire:model="password"
                                           minlength="6"
                                           class="form-control form-control-lg bg-light border-0"
                                           style="border-radius: 8px; font-size: 0.95rem;"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark small text-uppercase mb-2">Confirm Password *</label>
                                    <input type="password" 
                                           wire:model="passwordConfirm"
                                           class="form-control form-control-lg bg-light border-0"
                                           style="border-radius: 8px; font-size: 0.95rem;"
                                           required>
                                </div>
                            </div>
                        </div>

                        @if($error)
                        <div class="alert alert-danger rounded-lg border-0 mb-4">
                            {{ $error }}
                        </div>
                        @endif

                        @if($success)
                        <div class="alert alert-success rounded-lg border-0 mb-4">
                            {{ $success }}
                        </div>
                        @endif

                        <div id="googleBtnReg-register" class="mb-4" wire:ignore.self></div>
                        
                        <div class="text-center mb-4 position-relative">
                            <hr class="my-0">
                            <span class="position-absolute bg-white px-3 text-muted" style="top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 0.9rem;">OR</span>
                        </div>

                        <button type="submit" 
                                class="btn btn-primary btn-lg btn-block font-weight-bold shadow-sm"
                                style="background: #3CC78F; border: none; border-radius: 8px; padding: 12px;"
                                @if($loading) disabled @endif>
                            @if(!$loading)
                                Register
                            @else
                                <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>
                                Registering...
                            @endif
                        </button>

                        <p class="text-center mt-4 mb-0 text-muted">
                            Already have an account? 
                            <a href="#" 
                               class="text-success font-weight-bold text-decoration-none"
                               wire:click.prevent="close"
                               onclick="setTimeout(() => Livewire.dispatch('openLoginModal'), 100);">
                                Login here
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    @script
    <script>
        // Handle Google credential response - make it global
        window.handleGoogleCredentialResponse = function(response) {
            console.log('Google credential received, processing...');
            
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
        
        // Make functions globally accessible
        window.initGoogleRegistrationButton = function() {
            const buttonId = 'googleBtnReg-register';
            const buttonElement = document.getElementById(buttonId);
            
            if (!buttonElement) {
                console.warn('Google button element not found:', buttonId);
                return;
            }

            // Wait for Google script to load
            let retries = 0;
            function tryInit() {
                if (window.google && window.google.accounts && window.google.accounts.id) {
                    console.log('Initializing Google Sign-In button for registration');
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
                            text: "signup_with" 
                        }
                    );
                    console.log('Google Sign-In button rendered for registration');
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

        // Watch for modal show state and initialize button
        $wire.watch('show', (value) => {
            if (value) {
                console.log('Registration modal opened, initializing Google button');
                setTimeout(() => window.initGoogleRegistrationButton(), 300);
            }
        });

        // Also listen for the event
        Livewire.on('initGoogleRegistration', () => {
            console.log('Received initGoogleRegistration event');
            setTimeout(() => window.initGoogleRegistrationButton(), 100);
        });

        // Initialize on component mount if modal is already shown
        if ($wire.show) {
            setTimeout(() => window.initGoogleRegistrationButton(), 500);
        }
    </script>
    @endscript
</div>

