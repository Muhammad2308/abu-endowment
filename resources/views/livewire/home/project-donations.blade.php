<div class="popular_causes_area section_padding" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 50%, #e8f5f1 100%);">
    <div class="container">
        <!-- Header Section -->
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="section_title text-center mb-5">
                    <h3 style="color: #064e3b; font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem;">
                        Popular Projects
                    </h3>
                    <p class="text-muted" style="font-size: 1.1rem; line-height: 1.6;">
                        Support meaningful initiatives and help make a difference in communities around the world
                    </p>
                </div>
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="row">
            @forelse($projects as $project)
            <div class="col-lg-4 col-md-6 mb-4">
                @php
                    $raised = floatval($project->raised ?? 0);
                    $target = floatval($project->target ?? 0);
                    $percentage = ($target > 0) ? round(($raised / $target) * 100, 1) : 0;
                @endphp
                <div class="single_cause" style="background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #f0f0f0; height: 100%; display: flex; flex-direction: column;">
                    <div class="thumb" style="position: relative; height: 220px; overflow: hidden;">
                        <img src="{{ $project->icon_image ? asset('storage/' . $project->icon_image) : asset('img/causes/1.png') }}" 
                             alt="{{ $project->project_title }}" 
                             wire:click.prevent="openImageGallery({{ $project->id }})" 
                             style="cursor: pointer; width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                        
                        <!-- Need your support Badge -->
                        <div style="position: absolute; top: 0; right: 0; background: #ff5722; color: white; padding: 6px 16px; font-size: 0.85rem; font-weight: 600; border-radius: 0 0 0 8px; z-index: 2;">
                            Need your support
                        </div>
                        
                        <!-- Eye Icon Overlay -->
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(2px); pointer-events: none;">
                            <i class="fa fa-eye" style="color: #333; font-size: 1.2rem;"></i>
                        </div>
                    </div>
                    
                    <div class="causes_content" style="padding: 20px; flex: 1; display: flex; flex-direction: column;">
                        <!-- Project Title -->
                        <h4 style="font-size: 1.1rem; font-weight: 700; color: #1f2937; margin-bottom: 20px; border-left: 4px solid #ff5722; padding-left: 15px; line-height: 1.4;">
                            {{ $project->project_title }}
                        </h4>
                        
                        <!-- Stats Row -->
                        <div class="d-flex justify-content-between align-items-center mb-2" style="font-size: 0.85rem; color: #6b7280;">
                            <div>
                                Raised: <span style="color: #374151; font-weight: 500;">₦{{ number_format($project->raised ?? 0, 2) }}</span>
                            </div>
                            <div>
                                Goal: <span style="color: #374151; font-weight: 500;">₦{{ number_format($project->target ?? 0, 2) }}</span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress mb-3" style="height: 8px; border-radius: 4px; background-color: #e5e7eb; overflow: hidden;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ min($percentage, 100) }}%; background-color: #2e7d32; border-radius: 4px;" 
                                 aria-valuenow="{{ $percentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        
                        <!-- Project Description -->
                        <p style="color: #6b7280; font-size: 0.9rem; line-height: 1.6; margin-bottom: 25px; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; flex: 1;">
                            {{ $project->project_description }}
                        </p>
                        
                        <!-- Donate Button -->
                        <button wire:click="openDonationModal({{ $project->id }})" 
                                class="btn btn-block" 
                                style="background-color: #2e7d32; color: white; font-weight: 600; padding: 12px; border-radius: 6px; border: none; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 10px;">
                            <i class="fas fa-hand-holding-usd"></i> Support this project
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="p-5 bg-white rounded shadow-sm" style="max-width: 500px; margin: 0 auto;">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0" style="font-size: 1.1rem;">No active projects at the moment. Check back soon!</p>
                </div>
            </div>
            @endforelse
        </div>

        @if($limit)
        <div class="row justify-content-center mt-4">
            <div class="col-auto">
                <a href="{{ route('projects') }}" class="btn btn-outline-success rounded-pill px-5 py-3 font-weight-bold shadow-sm" style="border-width: 2px; transition: all 0.3s ease;">
                    Preview More Projects <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Bottom CTA -->
        <div class="row mt-5">
            <div class="col-12 text-center">
                <p class="text-muted mb-3" style="font-size: 1.1rem;">
                    Want to start your own project?
                </p>
                <button class="btn btn-lg" style="background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%); color: white; font-weight: 600; padding: 12px 40px; border-radius: 50px; border: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(16,185,129,0.3);">
                    Create a Project
                </button>
            </div>
        </div>
    </div>

    <!-- Donation Modal -->
    @if($showModal && $selectedProject)
    <div class="modal fade show d-block" id="donationModal" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
                <!-- Modal Header -->
                <div class="modal-header" style="background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%); border: none; padding: 1.5rem;">
                    <h5 class="modal-title text-white font-weight-bold">Donate to {{ $selectedProject->project_title }}</h5>
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

    <!-- Detailed Project View Modal -->
    @if($showImageGallery && $galleryProject)
    @php
        $galleryPhotos = [];
        // Add main project image
        $galleryPhotos[] = [
            'url' => $galleryProject->icon_image ? asset('storage/' . $galleryProject->icon_image) : asset('img/causes/1.png'),
            'description' => $galleryProject->project_description,
            'title' => $galleryProject->project_title
        ];
        // Add other photos
        foreach($galleryProject->photos as $photo) {
            $galleryPhotos[] = [
                'url' => asset('storage/' . $photo->body_image),
                'description' => $photo->description ?? '',
                'title' => $photo->title ?? ''
            ];
        }
    @endphp
    <div class="project-details-modal" style="display: block;" 
         x-data="{ 
            activeIndex: 0,
            showDesc: true,
            photos: {{ json_encode($galleryPhotos) }}
         }">
        
        <div class="project-details-overlay" wire:click="closeImageGallery"></div>
        
        <div class="project-details-container">
            <!-- Header -->
            <div class="project-details-header">
                <div class="d-flex align-items-center">
                    <div class="project-icon mr-3">
                        <img src="{{ $galleryProject->icon_image ? asset('storage/' . $galleryProject->icon_image) : asset('img/causes/1.png') }}" alt="Icon">
                    </div>
                    <div>
                        <h3 class="mb-0" style="font-weight: 700; font-size: 1.5rem; color: #064e3b;">{{ $galleryProject->project_title }}</h3>
                        <span class="text-muted" style="font-size: 0.9rem;">{{ count($galleryProject->photos) + 1 }} Photos</span>
                    </div>
                </div>
                <button type="button" class="close-btn" wire:click="closeImageGallery">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="project-details-body">
                <div class="row h-100">
                    <!-- Left Column: Image Viewer -->
                    <div class="col-lg-8 mb-4 mb-lg-0 d-flex flex-column">
                        <!-- Main Image -->
                        <div class="main-image-area mb-3 position-relative">
                            <template x-if="photos.length > 0">
                                <img :src="photos[activeIndex].url" class="main-image" :alt="photos[activeIndex].title">
                            </template>
                            
                            <!-- Image Counter -->
                            <div class="image-counter-badge">
                                <span x-text="(activeIndex + 1) + ' / ' + photos.length"></span>
                            </div>

                            <!-- Description Overlay -->
                            <div class="image-desc-overlay" x-show="showDesc && (photos[activeIndex].title || photos[activeIndex].description)" x-transition>
                                <button @click="showDesc = false" class="close-desc-btn"><i class="fa fa-times"></i></button>
                                <h5 x-text="photos[activeIndex].title || 'Photo Details'"></h5>
                                <p x-text="photos[activeIndex].description"></p>
                            </div>
                        </div>

                        <!-- Thumbnails -->
                        <div class="thumbnails-strip">
                            <template x-for="(photo, index) in photos" :key="index">
                                <div class="thumbnail-item" 
                                     :class="{'active': activeIndex === index}"
                                     @click="activeIndex = index; showDesc = true">
                                    <img :src="photo.url" alt="Thumbnail">
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Right Column: Info -->
                    <div class="col-lg-4">
                        <div class="project-info-sidebar">
                            <!-- Funding Status -->
                            <div class="info-card mb-4">
                                <h5 class="card-title">Funding Status</h5>
                                <div class="progress-wrapper mb-3">
                                    @php
                                        $raised = floatval($galleryProject->raised ?? 0);
                                        $target = floatval($galleryProject->target ?? 0);
                                        $percentage = ($target > 0) ? round(($raised / $target) * 100, 1) : 0;
                                    @endphp
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-success font-weight-bold">Raised</span>
                                        <span class="text-muted">Target</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 align-items-end">
                                        <span class="h4 mb-0 text-success font-weight-bold">₦{{ number_format($raised) }}</span>
                                        <span class="h6 mb-0 text-muted">₦{{ number_format($target) }}</span>
                                    </div>
                                    <div class="progress" style="height: 10px; border-radius: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2 small font-weight-bold">
                                        <span class="text-success">{{ $percentage }}% Funded</span>
                                        <span class="text-muted">₦{{ number_format(max(0, $target - $raised)) }} Remaining</span>
                                    </div>
                                </div>
                                <button wire:click="openDonationModal({{ $galleryProject->id }})" class="btn btn-success btn-block rounded-pill font-weight-bold py-2">
                                    Donate Now <i class="fa fa-heart ml-1"></i>
                                </button>
                            </div>

                            <!-- About Project -->
                            <div class="info-card">
                                <h5 class="card-title">About This Project</h5>
                                <div class="description-text">
                                    {{ $galleryProject->project_description }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .project-details-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .project-details-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            cursor: pointer;
        }

        .project-details-container {
            position: relative;
            width: 90%;
            max-width: 1200px;
            height: 90vh;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            z-index: 10001;
            animation: modalSlideUp 0.3s ease-out;
        }

        @keyframes modalSlideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .project-details-header {
            padding: 20px 30px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
        }

        .project-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .project-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .close-btn {
            background: #f3f4f6;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: #4b5563;
        }

        .close-btn:hover {
            background: #e5e7eb;
            color: #1f2937;
        }

        .project-details-body {
            flex: 1;
            overflow-y: auto;
            padding: 30px;
            background: #f9fafb;
        }

        /* Image Viewer Styles */
        .main-image-area {
            width: 100%;
            height: 500px;
            background: #000;
            border-radius: 15px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .image-counter-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(0,0,0,0.6);
            color: #fff;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            backdrop-filter: blur(4px);
        }

        .image-desc-overlay {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }

        .close-desc-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: transparent;
            border: none;
            color: #9ca3af;
            cursor: pointer;
        }

        .image-desc-overlay h5 {
            margin-bottom: 5px;
            font-weight: 700;
            color: #1f2937;
        }

        .image-desc-overlay p {
            margin-bottom: 0;
            font-size: 0.9rem;
            color: #4b5563;
        }

        .thumbnails-strip {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .thumbnail-item {
            width: 80px;
            height: 80px;
            flex-shrink: 0;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s;
            opacity: 0.7;
        }

        .thumbnail-item:hover {
            opacity: 1;
        }

        .thumbnail-item.active {
            border-color: #10b981;
            opacity: 1;
            transform: scale(1.05);
        }

        .thumbnail-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Sidebar Styles */
        .info-card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #f3f4f6;
            margin-bottom: 20px;
        }

        .card-title {
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ecfdf5;
            display: inline-block;
        }

        .description-text {
            color: #4b5563;
            line-height: 1.7;
            font-size: 0.95rem;
        }

        /* Scrollbar for thumbnails */
        .thumbnails-strip::-webkit-scrollbar {
            height: 6px;
        }
        .thumbnails-strip::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        .thumbnails-strip::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }

        @media (max-width: 991px) {
            .project-details-container {
                height: 100%;
                width: 100%;
                max-width: 100%;
                border-radius: 0;
            }
            .main-image-area {
                height: 300px;
            }
        }
    </style>
    @endif

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

            // Toast notification handler
            Livewire.on('show-toast', (data) => {
                const toastData = Array.isArray(data) ? data[0] : data;
                
                const toast = document.createElement('div');
                toast.className = `alert alert-${toastData.type} toast-notification`;
                toast.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 9999;
                    min-width: 300px;
                    padding: 15px 20px;
                    border-radius: 12px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                    animation: slideIn 0.3s ease-out;
                `;
                toast.innerHTML = `
                    <strong>${toastData.type === 'success' ? '✓' : '✗'}</strong> ${toastData.message}
                `;
                
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.style.animation = 'slideOut 0.3s ease-in';
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            });
        });
    </script>
</div>
