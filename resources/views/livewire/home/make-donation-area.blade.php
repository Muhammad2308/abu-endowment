<div id="make-donation" data-scroll-index="1" class="make_donation_area section_padding" style="background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%); padding: 100px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <span style="background: #dcfce7; color: #227722; padding: 8px 16px; border-radius: 30px; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; display: inline-block; margin-bottom: 15px;">
                        Support Our Mission
                    </span>
                    <h3 style="font-size: 2.5rem; font-weight: 800; color: #227722; margin-bottom: 1rem; letter-spacing: -0.5px;">
                        Make a Donation
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

                    <form wire:submit.prevent="donate" class="donation_form">
                        
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

                        <!-- Amount Selection -->
                        <div class="form-group mb-4">
                            <label class="font-weight-bold mb-3" style="color: #374151; font-size: 0.95rem;">Select Donation Amount</label>
                            <div class="amount-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 12px;">
                                
                                <label class="amount-option" style="cursor: pointer;">
                                    <input type="radio" wire:model.live="selectedAmount" value="1000" class="d-none">
                                    <div class="amount-card {{ $selectedAmount == 1000 ? 'active' : '' }}">
                                        <span class="amount-value">₦1k</span>
                                    </div>
                                </label>

                                <label class="amount-option" style="cursor: pointer;">
                                    <input type="radio" wire:model.live="selectedAmount" value="5000" class="d-none">
                                    <div class="amount-card {{ $selectedAmount == 5000 ? 'active' : '' }}">
                                        <span class="amount-value">₦5k</span>
                                    </div>
                                </label>

                                <label class="amount-option" style="cursor: pointer;">
                                    <input type="radio" wire:model.live="selectedAmount" value="10000" class="d-none">
                                    <div class="amount-card {{ $selectedAmount == 10000 ? 'active' : '' }}">
                                        <span class="amount-value">₦10k</span>
                                    </div>
                                </label>

                                <label class="amount-option" style="cursor: pointer;">
                                    <input type="radio" wire:model.live="selectedAmount" value="custom" class="d-none">
                                    <div class="amount-card {{ $selectedAmount == 'custom' ? 'active' : '' }}">
                                        <span class="amount-value">Custom</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Custom Amount Input (Conditional) -->
                        <div class="form-group mb-4" x-data="{ show: @entangle('selectedAmount') }" x-show="show == 'custom'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                            <label class="font-weight-bold mb-2" style="color: #374151; font-size: 0.95rem;">Enter Custom Amount</label>
                            <div class="input-group" style="background: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb; transition: all 0.3s ease;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text border-0 bg-transparent pl-3 font-weight-bold" style="color: #227722;">₦</span>
                                </div>
                                <input type="number" wire:model.live="customAmount" class="form-control border-0 bg-transparent" placeholder="e.g. 25000" style="height: 50px; padding-left: 5px; color: #1f2937; font-weight: 600; font-size: 1.1rem;">
                            </div>
                            @error('amount') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="mt-5">
                            <button type="submit" class="btn btn-block donate-btn" style="background: linear-gradient(135deg, #227722 0%, #1a5c1a 100%); color: white; font-weight: 700; padding: 16px; border-radius: 14px; border: none; font-size: 1.1rem; letter-spacing: 0.5px; box-shadow: 0 10px 20px rgba(34, 119, 34, 0.25); transition: all 0.3s ease; width: 100%;">
                                Donate Now <span wire:loading class="spinner-border spinner-border-sm ml-2"></span>
                            </button>
                            <p class="text-center mt-3 text-muted small">
                                <i class="fa fa-lock mr-1"></i> Secure payment powered by Paystack
                            </p>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .amount-card {
            background: #fff;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 15px 10px;
            text-align: center;
            transition: all 0.2s ease;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .amount-card:hover {
            border-color: #227722;
            background: #ecfdf5;
        }

        .amount-card.active {
            background: #ecfdf5;
            border-color: #227722;
            color: #227722;
            box-shadow: 0 4px 12px rgba(34, 119, 34, 0.15);
        }

        .amount-value {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .input-group:focus-within {
            border-color: #227722 !important;
            box-shadow: 0 0 0 3px rgba(34, 119, 34, 0.1);
            background: #fff !important;
        }

        .donate-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(34, 119, 34, 0.35) !important;
        }

        .donate-btn:active {
            transform: translateY(0);
        }
    </style>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('initiate-paystack', (data) => {
                const paymentData = Array.isArray(data) ? data[0] : data;
                
                let handler = PaystackPop.setup({
                    key: paymentData.key,
                    email: paymentData.email,
                    amount: paymentData.amount,
                    currency: paymentData.currency,
                    ref: paymentData.ref,
                    metadata: paymentData.metadata,
                    onClose: function(){
                        // alert('Window closed.');
                    },
                    callback: function(response){
                        Livewire.dispatch('payment-success', { reference: response.reference });
                    }
                });
                
                handler.openIframe();
            });
        });
    </script>
</div>
