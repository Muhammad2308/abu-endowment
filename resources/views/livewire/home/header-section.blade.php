<header>
    <div class="header-area">
        <!-- Top Bar -->
        <div class="header-top-bar" style="background: #fff; padding: 10px 0; border-bottom: 1px solid #eee;">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-6 d-none d-lg-block">
                        <div class="top-bar-left d-flex align-items-center" style="gap: 25px;">
                            <a href="tel:+2344545565656" style="color: #064e3b; font-size: 13px; font-weight: 500; text-decoration: none;">
                                <i class="fa fa-phone mr-2"></i> +234 (454) 556-5656
                            </a>
                            <a href="mailto:abuendowment@gmail.com" style="color: #064e3b; font-size: 13px; font-weight: 500; text-decoration: none;">
                                <i class="fa fa-envelope mr-2"></i> abuendowment@gmail.com
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="top-bar-right d-flex align-items-center justify-content-end" style="gap: 20px;">
                            <div class="social-icons d-flex align-items-center" style="gap: 15px;">
                                <a href="#" style="color: #064e3b;"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" style="color: #064e3b;"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" style="color: #064e3b;"><i class="fab fa-twitter"></i></a>
                            </div>
                            <div class="language-selector">
                                <a href="#" style="color: #064e3b; font-size: 13px; font-weight: 500; text-decoration: none;">
                                    <i class="fa fa-globe mr-1"></i> ENG <i class="fa fa-chevron-down ml-1" style="font-size: 10px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div id="sticky-header" class="main-header-area" style="background: #227722; padding: 15px 0; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <!-- Logo Section -->
                    <div class="col-6 col-lg-3">
                        <div class="logo">
                            <a href="{{ url('/') }}" class="d-flex align-items-center" style="text-decoration: none;">
                                <img src="{{ asset('abu_logo.png') }}" alt="ALUMNI FUND" style="height: 50px; width: auto; margin-right: 15px; filter: brightness(0) invert(1);">
                                <div class="logo-text" style="display: flex; flex-direction: column; line-height: 1.2;">
                                    <span style="font-size: 18px; font-weight: 700; color: #fff; letter-spacing: -0.5px;">Give ABU</span>
                                    <!-- <span style="font-size: 12px; font-weight: 400; color: rgba(255,255,255,0.9); letter-spacing: 0.5px;">& Crowd Funding</span> -->
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Menu Section (Desktop) -->
                    <div class="col-lg-9 d-none d-lg-block">
                        <div class="main-menu d-flex align-items-center justify-content-end">
                            <nav>
                                <ul id="navigation" class="d-flex align-items-center" style="gap: 40px; margin: 0;">
                                    <li><a href="{{ url('/') }}">Home</a></li>
                                    <li><a href="{{ route('about') }}">About</a></li>
                                    <li><a href="{{ route('projects') }}">Projects</a></li>

                                    
                                    <!-- Mobile Only Items -->
                                    <li class="d-lg-none"><a href="#make-donation">Make Donation</a></li>
                                    @if(!$isLoggedIn)
                                        <li class="d-lg-none"><a href="#" wire:click.prevent="$dispatch('openLoginModal')">Sign In</a></li>
                                    @else
                                        <li class="d-lg-none"><a href="#" wire:click.prevent="logout">Logout</a></li>
                                    @endif
                                </ul>
                            </nav>

                            <!-- Desktop Actions -->
                            <div class="header-right-btn d-flex align-items-center ml-5" style="gap: 15px;">
                                <!-- Auth Section -->
                                @if(!$isLoggedIn)
                                    <a href="#" wire:click.prevent="$dispatch('openLoginModal')" 
                                       class="btn header-btn-outline">
                                        <i class="fa fa-user mr-2"></i> Sign in
                                    </a>
                                @else
                                    <div class="dropdown">
                                        <a href="#" class="d-flex align-items-center" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-decoration: none; cursor: pointer; color: #fff;">
                                            @if(isset($user['avatar']) && $user['avatar'])
                                                <img src="{{ $user['avatar'] }}" alt="User" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #fff;">
                                            @else
                                                <div style="width: 35px; height: 35px; border-radius: 50%; background: rgba(255,255,255,0.2); color: #fff; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.5);">
                                                    <i class="fa fa-user" style="font-size: 14px;"></i>
                                                </div>
                                            @endif
                                            <span class="ml-2 font-weight-bold">{{ $user['name'] ?? 'User' }}</span>
                                            @if(!$isVerified)
                                                <span title="Email not verified" style="margin-left: 6px; font-size: 14px; line-height: 1;">&#9888;&#65039;</span>
                                            @endif
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown" style="border-radius: 0px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-top: 10px; min-width: 230px;">
                                            <div class="px-3 py-2 border-bottom">
                                                <small class="text-muted">{{ $user['email'] ?? '' }}</small>
                                            </div>
                                            @if(!$isVerified)
                                                <div style="background: #fffbeb; border-bottom: 1px solid #fde68a; padding: 10px 16px;">
                                                    <p style="color: #92400e; font-size: 0.78rem; margin: 0 0 6px; font-weight: 600;">&#9888; Email not verified</p>
                                                    @if($verificationResent)
                                                        <p style="color: #065f46; font-size: 0.75rem; margin: 0;">&#10003; Verification email sent — check your inbox.</p>
                                                    @else
                                                        <a href="#" wire:click.prevent="resendVerification" style="color: #064e3b; font-size: 0.78rem; font-weight: 600; text-decoration: underline;">Resend verification email</a>
                                                    @endif
                                                </div>
                                            @endif
                                            <a class="dropdown-item py-2" href="#" wire:click.prevent="$dispatch('openEditProfileModal')">Profile</a>
                                            <a class="dropdown-item py-2 text-danger" href="#" wire:click.prevent="logout">Logout</a>
                                        </div>
                                    </div>
                                @endif

                                <!-- Donate Button -->
                                <a href="#" id="squadDonateBtn" class="btn header-btn-fill" onclick="openDonationModal(event)">
                                    <i class="fa fa-heart mr-2"></i> Make Donation
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile Menu Toggle -->
                    <div class="col-6 d-lg-none">
                        <div class="mobile_menu d-flex justify-content-end"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap');

        /* Desktop Menu Links */
        #navigation li a {
            color: #fff;
            font-weight: 500;
            font-size: 15px;
        }
        
        /* Typography Updates */
        .logo-text span:first-child,
        .header-btn-outline,
        .header-btn-fill,
        .dropdown-item.text-danger {
            font-family: 'Playfair Display', serif !important;
        }

        /* Mobile Menu Fixes */
        .slicknav_nav {
            background: #fff;
            margin-top: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .slicknav_nav a {
            color: #333 !important; /* Force dark color for mobile menu links */
            margin: 0;
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
        }
        .slicknav_nav a:hover {
            background: #f9fafb;
            color: #227722 !important;
        }
        
        /* Hamburger Menu Alignment */
        .slicknav_menu {
            background: transparent;
            padding: 0;
            margin: 0;
        }
        .slicknav_btn {
            margin: 5px 0 0 0;
            background-color: transparent;
            float: right;
        }
        .slicknav_icon-bar {
            background-color: #fff !important; /* White hamburger lines */
            height: 3px;
            margin-bottom: 4px;
        }
        
        /* Remove Rounded Edges Globally for Header Elements */
        .header-area, 
        .main-header-area, 
        .slicknav_nav,
        .dropdown-menu {
            border-radius: 0px !important;
        }

        /* Button Styles */
        .header-btn-outline {
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
            padding: 10px 30px;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.3s;
            border-radius: 50px !important;
        }
        .header-btn-outline:hover {
            background: #fff;
            color: #227722;
        }
        
        .header-btn-fill {
            background-color: #fff;
            color: #227722;
            border: none;
            padding: 10px 30px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s;
            border-radius: 50px !important;
        }
        .header-btn-fill:hover {
            background-color: #f0fdf4;
        }
    </style>

    {{-- ═══════════════════════════════════════════════════════════
         DONATION MODAL
    ═══════════════════════════════════════════════════════════ --}}
    <div id="donationModal" style="display:none;position:fixed;inset:0;z-index:99999;align-items:center;justify-content:center;padding:1rem;">
        {{-- Backdrop --}}
        <div id="donationBackdrop" onclick="closeDonationModal()"
             style="position:absolute;inset:0;background:rgba(0,0,0,0.55);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);"></div>

        {{-- Modal card --}}
        <div id="donationCard" style="
            position:relative;z-index:1;width:100%;max-width:480px;
            background:#fff;border-radius:24px;overflow:hidden;
            box-shadow:0 32px 80px rgba(0,0,0,0.3);
            transform:translateY(30px);opacity:0;
            transition:transform 0.4s cubic-bezier(0.22,1,0.36,1),opacity 0.4s ease;
        ">
            {{-- Top gradient band --}}
            <div style="background:linear-gradient(135deg,#064e3b,#059669);padding:2rem 2rem 2.5rem;position:relative;text-align:center;">
                <button onclick="closeDonationModal()" style="
                    position:absolute;top:1rem;right:1rem;
                    background:rgba(255,255,255,0.15);border:none;color:#fff;
                    width:32px;height:32px;border-radius:50%;cursor:pointer;
                    font-size:1.1rem;display:flex;align-items:center;justify-content:center;
                    transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">✕</button>
                <img src="{{ asset('abu_logo_white_for_email.png') }}" alt="GIVE ABU" style="height:60px;width:auto;margin-bottom:0.75rem;display:block;margin-left:auto;margin-right:auto;">
                <h2 style="color:#fff;font-size:1.45rem;font-weight:700;margin:0 0 0.35rem;">Make a Difference Today</h2>
                <p style="color:rgba(255,255,255,0.82);font-size:0.875rem;margin:0;">Your donation builds the future of ABU</p>
                {{-- Wave --}}
                <div style="position:absolute;bottom:-1px;left:0;right:0;line-height:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 40" preserveAspectRatio="none" style="display:block;width:100%;">
                        <path d="M0,40 C480,0 960,0 1440,40 L1440,40 L0,40 Z" fill="#ffffff"/>
                    </svg>
                </div>
            </div>

            {{-- Form body --}}
            <div style="padding:1.75rem 2rem 2rem;">
                <div id="donationError" style="display:none;background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;border-radius:10px;padding:0.75rem 1rem;font-size:0.85rem;margin-bottom:1rem;"></div>

                {{-- Amount --}}
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block;font-size:0.78rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:#6b7280;margin-bottom:0.5rem;">Donation Amount <span style="color:#dc2626;">*</span></label>
                    <div style="display:flex;align-items:center;border:2px solid #d1d5db;border-radius:10px;overflow:hidden;transition:border-color 0.2s;" onfocusin="this.style.borderColor='#059669'" onfocusout="this.style.borderColor='#d1d5db'">
                        <span style="padding:0 0.75rem;font-weight:700;color:#059669;font-size:1rem;background:#f0fdf4;align-self:stretch;display:flex;align-items:center;border-right:1px solid #d1d5db;">₦</span>
                        <input type="number" id="donorAmountInput" min="100" placeholder="Enter amount (min ₦100)"
                            style="flex:1;border:none;outline:none;padding:0.65rem 0.75rem;font-size:0.9rem;color:#1f2937;background:#fff;">
                    </div>
                </div>

                {{-- Name (hidden when logged in) --}}
                @if(!$isLoggedIn)
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block;font-size:0.78rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:#6b7280;margin-bottom:0.4rem;">Full Name <span style="color:#dc2626;">*</span></label>
                    <input type="text" id="donorNameInput" placeholder="Your full name"
                        style="width:100%;border:2px solid #d1d5db;border-radius:10px;padding:0.65rem 0.85rem;font-size:0.9rem;outline:none;color:#1f2937;transition:border-color 0.2s;box-sizing:border-box;"
                        onfocus="this.style.borderColor='#059669'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                @endif

                {{-- Email (hidden when logged in) --}}
                @if(!$isLoggedIn)
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;font-size:0.78rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:#6b7280;margin-bottom:0.4rem;">Email Address <span style="color:#dc2626;">*</span></label>
                    <input type="email" id="donorEmailInput" placeholder="you@example.com"
                        style="width:100%;border:2px solid #d1d5db;border-radius:10px;padding:0.65rem 0.85rem;font-size:0.9rem;outline:none;color:#1f2937;transition:border-color 0.2s;box-sizing:border-box;"
                        onfocus="this.style.borderColor='#059669'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                @endif

                {{-- Two payment buttons --}}
                <div style="display:flex;gap:0.75rem;margin-bottom:0.85rem;">
                    {{-- Paystack --}}
                    <button id="paystackBtn" onclick="payWithPaystack()" style="
                        flex:1;padding:0.85rem 0.5rem;
                        background:linear-gradient(135deg,#064e3b,#059669);
                        color:#fff;font-size:0.88rem;font-weight:700;
                        border:none;border-radius:14px;cursor:pointer;
                        box-shadow:0 5px 18px rgba(6,78,59,0.3);
                        transition:transform 0.2s,box-shadow 0.2s;display:flex;align-items:center;justify-content:center;gap:0.4rem;"
                        onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 24px rgba(6,78,59,0.4)'"
                        onmouseout="this.style.transform='';this.style.boxShadow='0 5px 18px rgba(6,78,59,0.3)'">
                        <span id="paystackBtnText" style="display:flex;align-items:center;gap:0.4rem;">
                            <svg style="width:15px;height:15px;fill:#fff;" viewBox="0 0 24 24"><path d="M2 7h20v3H2zm0 4h20v3H2zm0 4h12v3H2z"/></svg>
                            Pay via Paystack
                        </span>
                        <span id="paystackSpinner" style="display:none;align-items:center;gap:0.4rem;">
                            <svg style="animation:spin 1s linear infinite;width:16px;height:16px;" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="rgba(255,255,255,0.3)" stroke-width="3"/>
                                <path d="M12 2a10 10 0 0 1 10 10" stroke="white" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                            Processing...
                        </span>
                    </button>

                    {{-- Squad --}}
                    <button id="squadBtn" onclick="payWithSquad()" style="
                        flex:1;padding:0.85rem 0.5rem;
                        background:linear-gradient(135deg,#7f1d1d,#dc2626);
                        color:#fff;font-size:0.88rem;font-weight:700;
                        border:none;border-radius:14px;cursor:pointer;
                        box-shadow:0 5px 18px rgba(185,28,28,0.3);
                        transition:transform 0.2s,box-shadow 0.2s;display:flex;align-items:center;justify-content:center;gap:0.4rem;"
                        onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 24px rgba(185,28,28,0.4)'"
                        onmouseout="this.style.transform='';this.style.boxShadow='0 5px 18px rgba(185,28,28,0.3)'">
                        <span id="squadBtnText" style="display:flex;align-items:center;gap:0.4rem;">
                            <svg style="width:15px;height:15px;fill:#fff;" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
                            Pay via Squad
                        </span>
                        <span id="squadSpinner" style="display:none;align-items:center;gap:0.4rem;">
                            <svg style="animation:spin 1s linear infinite;width:16px;height:16px;" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="rgba(255,255,255,0.3)" stroke-width="3"/>
                                <path d="M12 2a10 10 0 0 1 10 10" stroke="white" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>

                {{-- Trust badge --}}
                <p style="text-align:center;font-size:0.75rem;color:#9ca3af;margin-top:0.25rem;">
                    🔒 256-bit SSL &middot; Paystack &middot; Squad
                </p>
            </div>
        </div>
    </div>

    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>

    <script>
        var _authUserEmail = @json($isLoggedIn && !empty($user['email']) ? $user['email'] : null);
        var _authUserName  = @json($isLoggedIn && !empty($user['name']) ? $user['name'] : null);

        function openDonationModal(e) {
            if (e) e.preventDefault();
            const modal = document.getElementById('donationModal');
            const card  = document.getElementById('donationCard');
            modal.style.display = 'flex';
            requestAnimationFrame(() => {
                card.style.transform = 'translateY(0)';
                card.style.opacity   = '1';
            });
            document.body.style.overflow = 'hidden';
        }

        function closeDonationModal() {
            const modal = document.getElementById('donationModal');
            const card  = document.getElementById('donationCard');
            card.style.transform = 'translateY(30px)';
            card.style.opacity   = '0';
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }, 350);
        }

        function showDonationError(msg) {
            const el = document.getElementById('donationError');
            el.textContent = msg;
            el.style.display = 'block';
        }
        function hideDonationError() {
            document.getElementById('donationError').style.display = 'none';
        }

        function _validateInputs() {
            const amount = parseFloat(document.getElementById('donorAmountInput').value);
            if (!amount || isNaN(amount) || amount < 100) {
                showDonationError('Please enter a valid amount (minimum ₦100).');
                return null;
            }

            // When logged in the fields are hidden — use the server-provided values
            const nameEl  = document.getElementById('donorNameInput');
            const emailEl = document.getElementById('donorEmailInput');

            const fullName = nameEl ? nameEl.value.trim() : (_authUserName || '');
            if (!fullName) {
                showDonationError('Please enter your full name.');
                return null;
            }

            const email = emailEl ? emailEl.value.trim() : (_authUserEmail || '');
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showDonationError('Please enter a valid email address.');
                return null;
            }

            return { amount, fullName, email };
        }

        function _setLoading(btnId, spinnerId, textId, loading) {
            document.getElementById(btnId).disabled        = loading;
            document.getElementById(spinnerId).style.display = loading ? 'flex' : 'none';
            document.getElementById(textId).style.display    = loading ? 'none' : 'flex';
        }

        async function payWithPaystack() {
            hideDonationError();
            const inputs = _validateInputs();
            if (!inputs) return;

            const { amount, fullName, email } = inputs;
            const parts   = fullName.trim().split(/\s+/);
            const name    = parts[0];
            const surname = parts.length > 1 ? parts.slice(1).join(' ') : parts[0];

            _setLoading('paystackBtn', 'paystackSpinner', 'paystackBtnText', true);

            try {
                const callbackUrl = window.location.origin + '/api/payments/verify?redirect=' + encodeURIComponent(window.location.origin + '/');

                const res = await fetch('/api/payments/initialize', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '',
                    },
                    body: JSON.stringify({
                        email,
                        amount,
                        metadata: {
                            name,
                            surname,
                            other_name: null,
                            phone: null,
                            endowment: 'yes',
                            type: 'endowment',
                            project_id: null,
                        },
                        callback_url: callbackUrl,
                    }),
                });

                const result = await res.json();
                if (!res.ok) throw new Error(result.message || 'Unable to initialize Paystack payment.');
                if (!result.data?.authorization_url) throw new Error('Paystack did not return a payment URL.');

                window.location.href = result.data.authorization_url;

            } catch (err) {
                showDonationError(err.message || 'Failed to initiate payment. Please try again.');
                _setLoading('paystackBtn', 'paystackSpinner', 'paystackBtnText', false);
            }
        }

        async function payWithSquad() {
            hideDonationError();
            const inputs = _validateInputs();
            if (!inputs) return;

            const { amount, fullName, email } = inputs;

            _setLoading('squadBtn', 'squadSpinner', 'squadBtnText', true);

            try {
                const res = await fetch('{{ route('api.squad.pay') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '',
                    },
                    body: JSON.stringify({ amount, email, customer_name: fullName }),
                });

                const result = await res.json();
                if (!res.ok) throw new Error(result.message || 'Unable to initialize Squad payment.');
                if (!result.checkout_url) throw new Error('Squad did not return a checkout URL.');

                window.location.href = result.checkout_url;

            } catch (err) {
                showDonationError(err.message || 'Failed to initiate payment. Please try again.');
                _setLoading('squadBtn', 'squadSpinner', 'squadBtnText', false);
            }
        }

        // Close on Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeDonationModal();
        });
    </script>
</header>
