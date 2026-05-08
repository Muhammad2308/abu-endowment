<div class="project-contents-area">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-muted">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects') }}" class="text-muted">Projects</a></li>
                        <li class="breadcrumb-item active text-success" aria-current="page">{{ $project->project_title }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Banner & Title Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="project-banner position-relative rounded-lg overflow-hidden" style="height: 400px; background-color: #f0f0f0;">
                    <img src="{{ $project->icon_image ? asset('storage/' . $project->icon_image) : asset('img/causes/1.png') }}" 
                         alt="{{ $project->project_title }}" 
                         class="w-100 h-100" 
                         style="object-fit: cover;">
                    
                    <div class="banner-overlay position-absolute w-100 h-100 d-flex flex-column justify-content-end p-4 p-md-5" 
                         style="top: 0; left: 0; background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);">
                        <span class="badge badge-success mb-3 px-3 py-2" style="width: fit-content; background-color: #064e3b;">EDUCATION & SUPPORT</span>
                        <h1 class="text-white font-weight-bold mb-2" style="font-size: 2.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            {{ $project->project_title }}
                        </h1>
                        <p class="text-white-50 mb-0" style="font-size: 1.1rem; max-width: 800px;">
                            {{ Str::limit($project->project_description, 150) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- The Challenge / Description -->
                <div class="mb-5">
                    <h3 class="font-weight-bold mb-3" style="color: #1f2937;">The Challenge</h3>
                    <div class="text-muted" style="font-size: 1.05rem; line-height: 1.8;">
                        <p>{{ $project->project_description }}</p>
                    </div>
                </div>

                <!-- How Your Donation Helps (Static for now, could be dynamic) -->
                <div class="mb-5">
                    <h4 class="font-weight-bold mb-4" style="color: #1f2937;">How Your Donation Helps</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start p-3 rounded" style="background: #f9fafb; border: 1px solid #e5e7eb;">
                                <div class="icon-box mr-3 text-success">
                                    <i class="fa fa-graduation-cap fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1">Full Tuition Coverage</h6>
                                    <p class="small text-muted mb-0">Covering 100% of academic fees for top-performing indigent students.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start p-3 rounded" style="background: #f9fafb; border: 1px solid #e5e7eb;">
                                <div class="icon-box mr-3 text-success">
                                    <i class="fa fa-home fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1">Living Stipends</h6>
                                    <p class="small text-muted mb-0">Monthly allowances for food, accommodation, and study materials.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start p-3 rounded" style="background: #f9fafb; border: 1px solid #e5e7eb;">
                                <div class="icon-box mr-3 text-success">
                                    <i class="fa fa-users fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1">Mentorship Programs</h6>
                                    <p class="small text-muted mb-0">Connecting scholars with alumni for career guidance and networking.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start p-3 rounded" style="background: #f9fafb; border: 1px solid #e5e7eb;">
                                <div class="icon-box mr-3 text-success">
                                    <i class="fa fa-laptop fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1">Tech Grants</h6>
                                    <p class="small text-muted mb-0">Providing laptops and internet access for modern learning needs.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Green Highlight Section -->
                <div class="green-highlight p-4 p-md-5 rounded-lg mb-5 position-relative overflow-hidden" 
                     style="background-color: #064e3b; color: white;">
                    <div class="row align-items-center position-relative" style="z-index: 2;">
                        <div class="col-md-3 mb-3 mb-md-0 text-center">
                            <div class="rounded-circle overflow-hidden mx-auto" style="width: 100px; height: 100px; border: 3px solid rgba(255,255,255,0.2);">
                                <img src="{{ $project->icon_image ? asset('storage/' . $project->icon_image) : asset('img/causes/1.png') }}" 
                                     alt="Icon" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <i class="fa fa-quote-left fa-2x mb-3" style="opacity: 0.3;"></i>
                            <p class="font-italic mb-3" style="font-size: 1.1rem; line-height: 1.6; color: #e5e7eb;">
                                "The ABU Endowment Scholarship didn't just pay my fees; it gave me the peace of mind to focus on my studies. Today, I'm graduating with First Class Honors because someone believed in me."
                            </p>
                            <div>
                                <h6 class="font-weight-bold mb-0">Amina Yusuf</h6>
                                <small style="opacity: 0.7;">Computer Science, Class of 2023</small>
                            </div>
                            <i class="fa fa-quote-right fa-2x position-absolute" style="bottom: 0; right: 15px; opacity: 0.3;"></i>
                        </div>
                    </div>
                    <!-- Decorative background element -->
                    <div class="position-absolute" style="top: -20px; right: -20px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
                </div>

                <!-- Impact Gallery -->
                @if($project->photos && count($project->photos) > 0)
                <div class="mb-5">
                    <h4 class="font-weight-bold mb-4" style="color: #1f2937;">Impact Gallery</h4>
                    <div class="row">
                        @foreach($project->photos as $photo)
                        <div class="col-md-6 mb-4">
                            <div class="gallery-item rounded overflow-hidden shadow-sm position-relative group" style="height: 200px;">
                                <img src="{{ asset('storage/' . $photo->body_image) }}" 
                                     alt="{{ $photo->title ?? 'Gallery Image' }}" 
                                     class="w-100 h-100" 
                                     style="object-fit: cover; transition: transform 0.5s ease;">
                                <div class="overlay position-absolute w-100 h-100 d-flex align-items-end p-3" 
                                     style="top: 0; left: 0; background: linear-gradient(to top, rgba(0,0,0,0.7), transparent); opacity: 0; transition: opacity 0.3s ease;">
                                    <p class="text-white mb-0 small font-weight-bold">{{ $photo->title ?? 'Project Photo' }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar-widget p-4 bg-white rounded shadow-sm border mb-4" style="position: sticky; top: 100px;">
                    @php
                        $raised = floatval($project->raised ?? 0);
                        $target = floatval($project->target ?? 0);
                        $percentage = ($target > 0) ? round(($raised / $target) * 100, 1) : 0;
                    @endphp

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small font-weight-bold">Fundraising Progress</span>
                        <span class="text-muted small">of ₦{{ number_format($target/1000000, 1) }}M goal</span>
                    </div>

                    <h2 class="font-weight-bold text-success mb-1">₦{{ number_format($raised/1000000, 1) }}M</h2>
                    <div class="d-flex justify-content-between mb-3 small">
                        <span class="text-success font-weight-bold">{{ $percentage }}% Funded</span>
                        <span class="text-muted">1,280 Donors</span>
                    </div>

                    <div class="progress mb-4" style="height: 8px; border-radius: 4px; background-color: #e5e7eb;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ min($percentage, 100) }}%; border-radius: 4px;"></div>
                    </div>

                    <button wire:click="openDonationModal" class="btn btn-block btn-lg mb-3" 
                            style="background-color: #064e3b; color: white; font-weight: 600; border-radius: 50px;">
                        Donate Now <i class="fa fa-arrow-right ml-2"></i>
                    </button>

                    <button class="btn btn-block btn-outline-secondary btn-sm mb-3" style="border-radius: 50px;">
                        <i class="fa fa-share-alt mr-2"></i> Share this Cause
                    </button>

                    <div class="text-center">
                        <small class="text-muted"><i class="fa fa-lock mr-1"></i> Secure SSL Encrypted Transaction</small>
                    </div>

                    <hr class="my-4">

                    <h6 class="font-weight-bold mb-3">Project Details</h6>
                    <ul class="list-unstyled text-muted small" style="line-height: 2;">
                        <li class="d-flex justify-content-between">
                            <span>Beneficiaries:</span>
                            <span class="text-dark">Undergraduate Students</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Duration:</span>
                            <span class="text-dark">Ongoing (Annual)</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Location:</span>
                            <span class="text-dark">Zaria Campus</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Category:</span>
                            <span class="text-dark">Education</span>
                        </li>
                    </ul>
                    
                    <div class="mt-4 pt-3 border-top">
                        <p class="small text-muted mb-0">
                            Have questions about this project? <a href="#" class="text-success font-weight-bold">Contact the Endowment Office</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Other Ways to Support / Other Projects -->
        <div class="mt-5 pt-5 border-top">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="font-weight-bold mb-0" style="color: #1f2937;">Other Ways to Support</h4>
                <a href="{{ route('projects') }}" class="text-success font-weight-bold small">View All Projects <i class="fa fa-arrow-right ml-1"></i></a>
            </div>
            
            <div class="row">
                @foreach($otherProjects as $other)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden">
                        <div class="position-relative" style="height: 180px;">
                            <img src="{{ $other->icon_image ? asset('storage/' . $other->icon_image) : asset('img/causes/1.png') }}" 
                                 class="card-img-top w-100 h-100" 
                                 style="object-fit: cover;" 
                                 alt="{{ $other->project_title }}">
                        </div>
                        <div class="card-body p-4">
                            <span class="text-uppercase text-muted small font-weight-bold mb-2 d-block">PROJECT</span>
                            <h5 class="card-title font-weight-bold mb-2">
                                <a href="{{ route('project.single', $other->id) }}" class="text-dark text-decoration-none">
                                    {{ Str::limit($other->project_title, 40) }}
                                </a>
                            </h5>
                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit($other->project_description, 80) }}
                            </p>
                            
                            @php
                                $oRaised = floatval($other->raised ?? 0);
                                $oTarget = floatval($other->target ?? 0);
                                $oPercent = ($oTarget > 0) ? round(($oRaised / $oTarget) * 100) : 0;
                            @endphp
                            
                            <div class="mb-3">
                                <span class="text-success font-weight-bold small">{{ $oPercent }}% Funded</span>
                            </div>
                            
                            <a href="{{ route('project.single', $other->id) }}" class="font-weight-bold text-success small text-decoration-none">
                                Donate <i class="fa fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Donation Modal -->
    @if($showModal)
    <div class="modal fade show d-block" id="donationModal" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
                <!-- Modal Header -->
                <div class="modal-header" style="background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%); border: none; padding: 1.5rem;">
                    <h5 class="modal-title text-white font-weight-bold">Donate to {{ $project->project_title }}</h5>
                    <button type="button" class="close text-white" wire:click="closeModal" style="opacity: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body" style="padding: 2rem;">
                    <form wire:submit.prevent="donate">
                        <!-- Email Input -->
                        <div class="form-group mb-4">
                            <label class="font-weight-600 mb-2" style="color: #374151;">Email Address</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="background: #f3f4f6; border: 1px solid #d1d5db; border-right: none;">
                                        <i class="fa fa-envelope text-muted"></i>
                                    </span>
                                </div>
                                <input type="email" wire:model="email" class="form-control" placeholder="your@email.com" required
                                       style="border-left: none; padding: 12px; border-color: #d1d5db;">
                            </div>
                            @error('email') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        </div>

                        <!-- Amount Selection -->
                        <div class="form-group mb-4">
                            <label class="font-weight-600 mb-3" style="color: #374151;">Select Amount</label>
                            <div class="row no-gutters mb-3" style="gap: 0.5rem;">
                                <div class="col">
                                    <input type="radio" id="modal_blns_1" wire:model.live="selectedAmount" value="1000" class="d-none">
                                    <label for="modal_blns_1" class="btn btn-outline-success btn-block mb-0" style="padding: 12px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                        1k
                                    </label>
                                </div>
                                <div class="col">
                                    <input type="radio" id="modal_blns_2" wire:model.live="selectedAmount" value="5000" class="d-none">
                                    <label for="modal_blns_2" class="btn btn-outline-success btn-block mb-0" style="padding: 12px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                        5k
                                    </label>
                                </div>
                                <div class="col">
                                    <input type="radio" id="modal_blns_3" wire:model.live="selectedAmount" value="10000" class="d-none">
                                    <label for="modal_blns_3" class="btn btn-outline-success btn-block mb-0" style="padding: 12px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                        10k
                                    </label>
                                </div>
                                <div class="col">
                                    <input type="radio" id="modal_Other" wire:model.live="selectedAmount" value="custom" class="d-none">
                                    <label for="modal_Other" class="btn btn-outline-success btn-block mb-0" style="padding: 12px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                        Other
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Custom Amount Input -->
                        <div class="form-group mb-4">
                            <label class="font-weight-600 mb-2" style="color: #374151;">Custom Amount</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="background: #f3f4f6; border: 1px solid #d1d5db; border-right: none; font-weight: 600;">
                                        ₦
                                    </span>
                                </div>
                                <input type="number" wire:model.live="customAmount" class="form-control" placeholder="Enter custom amount"
                                       style="border-left: none; padding: 12px; border-color: #d1d5db;">
                            </div>
                            @error('amount') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-block btn-lg" 
                                style="background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%); color: white; font-weight: 700; padding: 14px; border-radius: 12px; border: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(16,185,129,0.3);">
                            Donate ₦{{ number_format($amount, 2) }}
                            <span wire:loading class="spinner-border spinner-border-sm ml-2"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        .gallery-item:hover img {
            transform: scale(1.1);
        }
        .gallery-item:hover .overlay {
            opacity: 1 !important;
        }
    </style>

    <!-- Paystack Integration Script -->
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
                        console.log('Payment window closed.');
                    },
                    callback: function(response){
                        Livewire.dispatch('project-payment-success', { reference: response.reference });
                    }
                });
                
                handler.openIframe();
            });
        });
    </script>
</div>
