<div>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @if($show)
    <!-- Modal Backdrop -->
    <div class="modal-backdrop fade show" 
         wire:click="close"
         style="z-index: 9998; background-color: rgba(0,0,0,0.5); backdrop-filter: blur(5px);"></div>

    <!-- Modal Container -->
    <div class="modal fade show d-block" 
         tabindex="-1" 
         role="dialog" 
         style="z-index: 9999; overflow-y: hidden;">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document" 
        style="max-width: 95vw; width: 1400px;">
            <div class="modal-content border-0 shadow-lg" 
            style="border-radius: 20px; overflow: hidden; height: 90vh;">
                <div class="row h-100 m-0">
                    <!-- Left Side - Branding -->
                    <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center p-0 text-white position-relative overflow-hidden">
                        <!-- Background Image with Overlay -->
                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
                                    background: linear-gradient(to bottom right, rgba(6, 78, 59, 0.9), rgba(16, 185, 129, 0.8)), url('{{ asset('img/ABU SKY IMAGE.jpg') }}') center/cover no-repeat;
                                    mix-blend-mode: multiply;
                                    transform: scale(1.05);">
                        </div>

                        <!-- Slanted Divider (CSS Shape) -->
                        <div style="position: absolute; top: 0; right: -1px; width: 100px; height: 100%; background: #fff; 
                                    clip-path: polygon(100% 0, 0 0, 100% 100%); z-index: 2;"></div>

                        <!-- Content -->
                        <div class="position-relative w-100 p-5" 
                        style="z-index: 3; padding-left: 80px !important;">
                            <div class="mb-5">
                                <img src="{{ asset('abu_logo.png') }}" alt="ABU Logo" 
                                style="height: 100px; width: auto; filter: brightness(0) invert(1);">
                            </div>
                            <h1 class="font-weight-bold mb-4" 
                            style="font-family: 'Playfair Display', serif; font-size: 4.5rem; line-height: 1.1; color: #ffffff !important; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                Legacy &<br>Impact
                            </h1>
                            <p class="lead mb-0" style="font-family: 'Inter', sans-serif; font-size: 1.25rem; line-height: 1.6; max-width: 500px; color: #ffffff !important; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                                Empowering the future through the Ahmadu Bello University Endowment Foundation. 
                                Manage contributions, projects, and donor relations efficiently.
                            </p>
                        </div>
                    </div>

                    <!-- Right Side - Form -->
                    <div class="col-lg-6 bg-white h-100 position-relative">
                        <!-- Close Button -->
                        <button type="button" 
                                class="close position-absolute p-3" 
                                wire:click="close"
                                aria-label="Close"
                                style="right: 20px; top: 20px; z-index: 10; opacity: 0.4; transition: all 0.3s; outline: none !important; border: none !important; background: transparent !important; box-shadow: none !important;">
                            <span aria-hidden="true" style="font-size: 2.5rem; font-weight: 300;">&times;</span>
                        </button>

                        <div class="h-100 overflow-auto custom-scrollbar d-flex flex-column" style="padding: 3rem 4rem;">
                            <div class="mb-4">
                                <h2 class="font-weight-bold mb-1" style="color: #064e3b; font-family: 'Playfair Display', serif; font-size: 2rem;">Create Account</h2>
                                <p class="text-muted" style="font-family: 'Inter', sans-serif; font-size: 1rem;">Please enter your details to register.</p>
                            </div>

                            <!-- Google Login at Top -->
                            <div id="googleBtnReg-register" class="mb-3 d-flex justify-content-start" wire:ignore.self></div>

                            <div class="text-center mb-3 position-relative">
                                <hr class="my-0">
                                <span class="position-absolute bg-white px-3 text-muted" style="top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 0.8rem;">OR</span>
                            </div>

                            <form wire:submit.prevent="register">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-dark small text-uppercase mb-1" style="font-family: 'Inter', sans-serif; color: #374151; font-size: 0.75rem;">I am a: *</label>
                                    <select wire:model="donorType" 
                                            class="form-control form-control-lg border-0"
                                            style="background-color: #f9fafb; border: 1px solid #e5e7eb !important; border-radius: 0.5rem; font-size: 0.9rem; height: 45px; color: #374151;"
                                            required>
                                        <option value="">Select your type</option>
                                        <option value="addressable_alumni">Alumni</option>
                                        <option value="supporter">Supporter</option>
                                        <option value="non_addressable_alumni">Other</option>
                                    </select>
                                </div>

                                @if($showAlumniFields)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold text-dark small text-uppercase mb-1" style="font-family: 'Inter', sans-serif; color: #374151; font-size: 0.75rem;">Registration Number *</label>
                                            <input type="text" 
                                                   wire:model="regNumber"
                                                   placeholder="e.g., U16/ENG/1234"
                                                   class="form-control form-control-lg border-0"
                                                   style="background-color: #f9fafb; border: 1px solid #e5e7eb !important; border-radius: 0.5rem; font-size: 0.9rem; height: 45px; color: #374151;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold text-dark small text-uppercase mb-1" style="font-family: 'Inter', sans-serif; color: #374151; font-size: 0.75rem;">Entry Year</label>
                                            <input type="number" 
                                                   wire:model="entryYear"
                                                   min="1950" 
                                                   max="{{ date('Y') }}"
                                                   placeholder="e.g., 2016"
                                                   class="form-control form-control-lg border-0"
                                                   style="background-color: #f9fafb; border: 1px solid #e5e7eb !important; border-radius: 0.5rem; font-size: 0.9rem; height: 45px; color: #374151;">
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold text-dark small text-uppercase mb-1" style="font-family: 'Inter', sans-serif; color: #374151; font-size: 0.75rem;">Surname *</label>
                                            <input type="text" 
                                                   wire:model="surname"
                                                   class="form-control form-control-lg border-0"
                                                   style="background-color: #f9fafb; border: 1px solid #e5e7eb !important; border-radius: 0.5rem; font-size: 0.9rem; height: 45px; color: #374151;"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold text-dark small text-uppercase mb-1" style="font-family: 'Inter', sans-serif; color: #374151; font-size: 0.75rem;">First Name *</label>
                                            <input type="text" 
                                                   wire:model="name"
                                                   class="form-control form-control-lg border-0"
                                                   style="background-color: #f9fafb; border: 1px solid #e5e7eb !important; border-radius: 0.5rem; font-size: 0.9rem; height: 45px; color: #374151;"
                                                   required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-dark small text-uppercase mb-1" style="font-family: 'Inter', sans-serif; color: #374151; font-size: 0.75rem;">Other Name (Optional)</label>
                                    <input type="text" 
                                           wire:model="otherName"
                                           class="form-control form-control-lg border-0"
                                           style="background-color: #f9fafb; border: 1px solid #e5e7eb !important; border-radius: 0.5rem; font-size: 0.9rem; height: 45px; color: #374151;">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold text-dark small text-uppercase mb-1" style="font-family: 'Inter', sans-serif; color: #374151; font-size: 0.75rem;">Email *</label>
                                            <input type="email" 
                                                   wire:model="email"
                                                   class="form-control form-control-lg border-0"
                                                   style="background-color: #f9fafb; border: 1px solid #e5e7eb !important; border-radius: 0.5rem; font-size: 0.9rem; height: 45px; color: #374151;"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold text-dark small text-uppercase mb-1" style="font-family: 'Inter', sans-serif; color: #374151; font-size: 0.75rem;">Phone Number *</label>
                                            <input type="tel" 
                                                   wire:model="phone"
                                                   class="form-control form-control-lg border-0"
                                                   style="background-color: #f9fafb; border: 1px solid #e5e7eb !important; border-radius: 0.5rem; font-size: 0.9rem; height: 45px; color: #374151;"
                                                   required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold text-dark small text-uppercase mb-1" style="font-family: 'Inter', sans-serif; color: #374151; font-size: 0.75rem;">State *</label>
                                            <input type="text" 
                                                   wire:model="state"
                                                   class="form-control form-control-lg border-0"
                                                   style="background-color: #f9fafb; border: 1px solid #e5e7eb !important; border-radius: 0.5rem; font-size: 0.9rem; height: 45px; color: #374151;"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold text-dark small text-uppercase mb-1" style="font-family: 'Inter', sans-serif; color: #374151; font-size: 0.75rem;">LGA *</label>
                                            <input type="text" 
                                                   wire:model="lga"
                                                   class="form-control form-control-lg border-0"
                                                   style="background-color: #f9fafb; border: 1px solid #e5e7eb !important; border-radius: 0.5rem; font-size: 0.9rem; height: 45px; color: #374151;"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold text-dark small text-uppercase mb-1" style="font-family: 'Inter', sans-serif; color: #374151; font-size: 0.75rem;">Nationality *</label>
                                            <input type="text" 
                                                   wire:model="nationality"
                                                   class="form-control form-control-lg border-0"
                                                   style="background-color: #f9fafb; border: 1px solid #e5e7eb !important; border-radius: 0.5rem; font-size: 0.9rem; height: 45px; color: #374151;"
                                                   required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-dark small text-uppercase mb-1" style="font-family: 'Inter', sans-serif; color: #374151; font-size: 0.75rem;">Username *</label>
                                    <input type="text" 
                                           wire:model="username"
                                           minlength="3"
                                           class="form-control form-control-lg border-0"
                                           style="background-color: #f9fafb; border: 1px solid #e5e7eb !important; border-radius: 0.5rem; font-size: 0.9rem; height: 45px; color: #374151;"
                                           required>
                                    <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Choose a unique username for login</small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold text-dark small text-uppercase mb-1" style="font-family: 'Inter', sans-serif; color: #374151; font-size: 0.75rem;">Password *</label>
                                            <input type="password" 
                                                   wire:model="password"
                                                   minlength="6"
                                                   class="form-control form-control-lg border-0"
                                                   style="background-color: #f9fafb; border: 1px solid #e5e7eb !important; border-radius: 0.5rem; font-size: 0.9rem; height: 45px; color: #374151;"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold text-dark small text-uppercase mb-1" style="font-family: 'Inter', sans-serif; color: #374151; font-size: 0.75rem;">Confirm Password *</label>
                                            <input type="password" 
                                                   wire:model="passwordConfirm"
                                                   class="form-control form-control-lg border-0"
                                                   style="background-color: #f9fafb; border: 1px solid #e5e7eb !important; border-radius: 0.5rem; font-size: 0.9rem; height: 45px; color: #374151;"
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

                                <button type="submit" 
                                        class="btn btn-primary btn-lg btn-block font-weight-bold shadow-sm mb-3"
                                        style="background-color: #064e3b; color: #ffffff; border: none; border-radius: 0.5rem; padding: 12px; height: auto; font-family: 'Inter', sans-serif; font-weight: 600; box-shadow: 0 10px 15px -3px rgba(251, 255, 254, 0.97);"
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
                                       class="font-weight-bold text-decoration-none"
                                       style="color: #064e3b;"
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

