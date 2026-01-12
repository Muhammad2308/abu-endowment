<div id="make-donation" data-scroll-index="1" class="make_donation_area section_padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section_title text-center mb-55">
                    <!-- <h3><span>Make a Donation</span></h3> -->
                </div>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if (session()->has('message'))
                    <div class="alert alert-success text-center">
                        {{ session('message') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger text-center">
                        {{ session('error') }}
                    </div>
                @endif

                <form wire:submit.prevent="donate" class="donation_form">
                    <div class="row align-items-center">
                        <div class="col-md-12 mb-4">
                            <div class="single_amount">
                                <!-- <div class="input_field">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        </div>
                                        <input type="email" wire:model="email" class="form-control" placeholder="Email Address" required>
                                    </div>
                                    @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div> -->
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="single_amount">
                                <!-- <div class="input_field">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">₦</span>
                                        </div>
                                        <input type="number" wire:model.live="customAmount" class="form-control" placeholder="Custom Amount" aria-label="Amount">
                                    </div>
                                    @error('amount') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div> -->
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="single_amount">
                                <!-- <div class="fixed_donat d-flex align-items-center justify-content-between flex-wrap">
                                    <div class="select_prise">
                                        <h4>Select Amount:</h4>
                                    </div>
                                    <div class="single_doonate">
                                        <input type="radio" id="blns_1" wire:model.live="selectedAmount" value="1000">
                                        <label for="blns_1">1k</label>
                                    </div>
                                    <div class="single_doonate">
                                        <input type="radio" id="blns_2" wire:model.live="selectedAmount" value="5000">
                                        <label for="blns_2">5k</label>
                                    </div>
                                    <div class="single_doonate">
                                        <input type="radio" id="blns_3" wire:model.live="selectedAmount" value="10000">
                                        <label for="blns_3">10k</label>
                                    </div>
                                    <div class="single_doonate">
                                        <input type="radio" id="Other" wire:model.live="selectedAmount" value="custom">
                                        <label for="Other">Other</label>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="donate_now_btn text-center">
                                <button type="submit" class="boxed-btn4 custom-green">
                                    Donate Now <span wire:loading class="spinner-border spinner-border-sm ml-2"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('initiate-paystack', (data) => {
                // Ensure data is an object (Livewire might wrap it in an array)
                const paymentData = Array.isArray(data) ? data[0] : data;
                
                let handler = PaystackPop.setup({
                    key: paymentData.key,
                    email: paymentData.email,
                    amount: paymentData.amount,
                    currency: paymentData.currency,
                    ref: paymentData.ref,
                    metadata: paymentData.metadata,
                    onClose: function(){
                        alert('Window closed.');
                    },
                    callback: function(response){
                        // Call Livewire method to verify payment
                        Livewire.dispatch('payment-success', { reference: response.reference });
                    }
                });
                
                handler.openIframe();
            });
        });
    </script>
</div>
