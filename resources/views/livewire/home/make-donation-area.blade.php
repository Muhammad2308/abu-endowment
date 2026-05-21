<div id="make-donation" data-scroll-index="1" class="make_donation_area section_padding" style="background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%); padding: 100px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h3 style="color: #064e3b; font-size: 36px; font-weight: 700; margin-bottom: 20px; font-family: 'Playfair Display', serif;">
                        Support Our Mission
                    </h3>
                    <p class="text-muted" style="font-size: 1.1rem; max-width: 600px; margin: 0 auto; line-height: 1.6;">
                        Your generous contribution helps us continue our work and make a lasting impact in the community.
                    </p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="donation-card" style="background: #fff; padding: 40px; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1); border: 1px solid rgba(0,0,0,0.03); position: relative; overflow: hidden;">

                    <!-- Decorative Top Border -->
                    <div style="position: absolute; top: 0; left: 0; right: 0; height: 6px; background: linear-gradient(90deg, #227722, #1a5c1a);"></div>

                    @if (session()->has('message'))
                        <div class="alert alert-success text-center mb-4" style="border-radius: 12px; background: #f0fdf4; border-color: #bbf7d0; color: #227722;">
                            <i class="fa fa-check-circle mr-2"></i> {{ session('message') }}
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-danger text-center mb-4" style="border-radius: 12px; background: #fef2f2; border-color: #fecaca; color: #991b1b;">
                            <i class="fa fa-exclamation-circle mr-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    {{-- Enter key triggers Paystack by default --}}
                    <form wire:submit.prevent="payWithPaystack" class="donation_form">

                        <!-- Email Field -->
                        <div class="form-group mb-4">
                            <label class="font-weight-bold mb-2" style="color: #374151; font-size: 0.95rem;">Email Address</label>
                            <div class="input-group" style="background: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb; transition: all 0.3s ease;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text border-0 bg-transparent pl-3">
                                        <i class="fa fa-envelope" style="color: #9ca3af;"></i>
                                    </span>
                                </div>
                                <input type="email" wire:model="email" class="form-control border-0 bg-transparent" placeholder="Enter your email address" required style="height: 50px; padding-left: 10px; color: #1f2937; font-weight: 500;">
                            </div>
                            @error('email') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Donation Amount -->
                        <div class="form-group mb-4">
                            <label class="font-weight-bold mb-2" style="color: #374151; font-size: 0.95rem;">Donation Amount</label>
                            <div class="input-group" style="background: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb; transition: all 0.3s ease;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text border-0 bg-transparent pl-3 font-weight-bold" style="color: #227722;">₦</span>
                                </div>
                                <input type="number" min="100" step="1" wire:model="amount" class="form-control border-0 bg-transparent" placeholder="Enter amount (e.g. 5000)" required style="height: 50px; padding-left: 5px; color: #1f2937; font-weight: 600; font-size: 1.1rem;">
                            </div>
                            @error('amount') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Payment Buttons -->
                        <div class="mt-5">
                            <p class="text-center text-muted mb-3" style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Choose Payment Method</p>

                            <div class="row" style="gap: 0; margin: 0 -6px;">
                                <!-- Paystack -->
                                <div class="col-12 col-sm-6" style="padding: 0 6px; margin-bottom: 12px;">
                                    <button type="submit"
                                            wire:loading.attr="disabled"
                                            wire:loading.class="btn-disabled"
                                            wire:target="payWithPaystack"
                                            class="pay-btn paystack-btn">
                                        <span wire:loading.remove wire:target="payWithPaystack" class="btn-inner">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                                <circle cx="12" cy="12" r="12" fill="white"/>
                                                <text x="12" y="16" text-anchor="middle" fill="#006aff" font-size="13" font-weight="900" font-family="Arial">P</text>
                                            </svg>
                                            Pay with Paystack
                                        </span>
                                        <span wire:loading wire:target="payWithPaystack" class="btn-inner">
                                            <svg class="spin-icon" viewBox="0 0 24 24" fill="none" width="18" height="18">
                                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity="0.3"/>
                                                <path fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                            </svg>
                                            Processing…
                                        </span>
                                    </button>
                                </div>

                                <!-- Squad -->
                                <div class="col-12 col-sm-6" style="padding: 0 6px; margin-bottom: 12px;">
                                    <button type="button"
                                            id="squad-pay-btn"
                                            wire:click="payWithSquad"
                                            wire:loading.attr="disabled"
                                            wire:loading.class="btn-disabled"
                                            wire:target="payWithSquad"
                                            class="pay-btn squad-btn">
                                        <span id="squad-btn-text" class="btn-inner">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                                <circle cx="12" cy="12" r="12" fill="white"/>
                                                <text x="12" y="16" text-anchor="middle" fill="#00b8a9" font-size="13" font-weight="900" font-family="Arial">S</text>
                                            </svg>
                                            Pay with Squad
                                        </span>
                                        <span id="squad-btn-loading" class="btn-inner" style="display:none;">
                                            <svg class="spin-icon" viewBox="0 0 24 24" fill="none" width="18" height="18">
                                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity="0.3"/>
                                                <path fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                            </svg>
                                            Redirecting…
                                        </span>
                                    </button>
                                </div>
                            </div>

                            <p class="text-center mt-2 text-muted small">
                                <i class="fa fa-lock mr-1"></i> Secure &amp; encrypted payment
                            </p>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes btn-spin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        .spin-icon { animation: btn-spin 0.8s linear infinite; }

        .pay-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 14px 16px;
            border-radius: 14px;
            border: none;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            color: white;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s, opacity 0.2s;
        }
        .pay-btn:hover { transform: translateY(-2px); }
        .pay-btn:active { transform: translateY(0); }
        .pay-btn.btn-disabled { opacity: 0.6; cursor: not-allowed; }

        .btn-inner { display: flex; align-items: center; gap: 8px; }

        .paystack-btn {
            background: linear-gradient(135deg, #006aff 0%, #0052cc 100%);
            box-shadow: 0 8px 20px rgba(0, 106, 255, 0.28);
        }
        .paystack-btn:hover { box-shadow: 0 12px 28px rgba(0, 106, 255, 0.4); }

        .squad-btn {
            background: linear-gradient(135deg, #00b8a9 0%, #007e76 100%);
            box-shadow: 0 8px 20px rgba(0, 184, 169, 0.28);
        }
        .squad-btn:hover { box-shadow: 0 12px 28px rgba(0, 184, 169, 0.4); }

        .input-group:focus-within {
            border-color: #227722 !important;
            box-shadow: 0 0 0 3px rgba(34, 119, 34, 0.1);
            background: #fff !important;
        }
    </style>

    <script>
        document.addEventListener('livewire:initialized', () => {

            // ── Paystack inline popup ──────────────────────────────────────
            Livewire.on('initiate-paystack', (data) => {
                const p = Array.isArray(data) ? data[0] : data;

                PaystackPop.setup({
                    key:      p.key,
                    email:    p.email,
                    amount:   p.amount,
                    currency: p.currency,
                    ref:      p.ref,
                    metadata: p.metadata,
                    onClose:  function () {},
                    callback: function (response) {
                        Livewire.dispatch('payment-success', { reference: response.reference });
                    },
                }).openIframe();
            });

            // ── Squad redirect ─────────────────────────────────────────────
            Livewire.on('initiate-squad', async (data) => {
                const p       = Array.isArray(data) ? data[0] : data;
                const btn     = document.getElementById('squad-pay-btn');
                const btnText = document.getElementById('squad-btn-text');
                const btnLoad = document.getElementById('squad-btn-loading');

                const showLoading = () => {
                    if (btn)     btn.disabled = true;
                    if (btnText) btnText.style.display = 'none';
                    if (btnLoad) btnLoad.style.display = 'flex';
                };
                const hideLoading = () => {
                    if (btn)     btn.disabled = false;
                    if (btnText) btnText.style.display = 'flex';
                    if (btnLoad) btnLoad.style.display = 'none';
                };

                showLoading();

                try {
                    const res = await fetch('/api/squad/pay', {
                        method:  'POST',
                        headers: {
                            'Content-Type':     'application/json',
                            'Accept':           'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            amount:        p.amount,
                            email:         p.email,
                            customer_name: p.customer_name || '',
                        }),
                    });

                    const result = await res.json();

                    if (result.checkout_url) {
                        window.location.href = result.checkout_url;
                    } else {
                        alert(result.message || 'Unable to initiate Squad payment. Please try again.');
                        hideLoading();
                    }
                } catch (err) {
                    console.error('Squad payment error:', err);
                    alert('A network error occurred. Please check your connection and try again.');
                    hideLoading();
                }
            });

        });
    </script>
</div>
