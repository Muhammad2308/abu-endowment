<div class="popular_causes_area section_padding" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 50%, #e8f5f1 100%);">
    <div class="container">
        <!-- Header Section -->
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="section_title text-center mb-5">
                    <h3 style="background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem;">
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
                <div class="single_cause" style="border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: all 0.3s ease; border: none;">
                    <div class="thumb" style="position: relative; overflow: hidden;">
                        <img src="{{ $project->icon_image ? asset('storage/' . $project->icon_image) : asset('img/causes/1.png') }}" 
                             alt="{{ $project->project_title }}" 
                             wire:click.prevent="openImageGallery({{ $project->id }})" 
                             style="cursor: pointer; width: 100%; height: 250px; object-fit: cover; transition: transform 0.5s ease;">
                        
                        <!-- Category Badge -->
                        <div style="position: absolute; top: 15px; left: 15px;">
                            <span class="badge badge-light" style="padding: 8px 16px; font-size: 0.85rem; font-weight: 600; border-radius: 20px; background: rgba(255,255,255,0.95); color: #333; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                {{ $project->category_id ? 'Infrastructure' : 'Education' }}
                            </span>
                        </div>
                        
                        <!-- Progress Badge -->
                        <div style="position: absolute; top: 15px; right: 15px;">
                            @php
                                $raised = floatval($project->raised ?? 0);
                                $target = floatval($project->target ?? 0);
                                $percentage = ($target > 0) ? round(($raised / $target) * 100, 1) : 0;
                            @endphp
                            <span class="badge" style="padding: 8px 16px; font-size: 0.85rem; font-weight: 700; border-radius: 20px; background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%); color: white; box-shadow: 0 2px 8px rgba(16,185,129,0.3);">
                                {{ $percentage }}%
                            </span>
                        </div>
                    </div>
                    
                    <div class="causes_content" style="padding: 1.5rem;">
                        <!-- Project Title -->
                        <h4 style="font-size: 1.25rem; font-weight: 700; color: #1f2937; margin-bottom: 0.75rem; line-height: 1.4;">
                            {{ $project->project_title }}
                        </h4>
                        
                        <!-- Project Description -->
                        <p style="color: #6b7280; font-size: 0.95rem; line-height: 1.6; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $project->project_description }}
                        </p>
                        
                        <!-- Progress Bar -->
                        <div class="custom_progress_bar mb-3">
                            <div class="progress" style="height: 8px; border-radius: 10px; background-color: #e5e7eb;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ min($percentage, 100) }}%; background: linear-gradient(90deg, #10b981 0%, #14b8a6 100%); border-radius: 10px; transition: width 0.5s ease;" 
                                     aria-valuenow="{{ $percentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Raised and Goal -->
                        <div class="balance d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <small class="text-muted d-block" style="font-size: 0.75rem; margin-bottom: 0.25rem;">Raised</small>
                                <span style="font-weight: 700; color: #10b981; font-size: 0.95rem;">₦{{ number_format($project->raised ?? 0, 2) }}</span>
                            </div>
                            <div class="text-right">
                                <small class="text-muted d-block" style="font-size: 0.75rem; margin-bottom: 0.25rem;">Goal</small>
                                <span style="font-weight: 700; color: #1f2937; font-size: 0.95rem;">₦{{ number_format($project->target ?? 0, 2) }}</span>
                            </div>
                        </div>
                        
                        <!-- Donate Button -->
                        <button wire:click="openDonationModal({{ $project->id }})" 
                                class="btn btn-block" 
                                style="background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%); color: white; font-weight: 600; padding: 12px; border-radius: 10px; border: none; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(16,185,129,0.2);">
                            Donate Now
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

    <!-- Image Gallery Modal (keeping existing functionality) -->
    @if($showImageGallery && $galleryProject)
    <div class="image-gallery-modal" id="imageGalleryModal" style="display: block;">
        <div class="image-gallery-overlay" wire:click="closeImageGallery"></div>
        <div class="image-gallery-container">
            <!-- Header -->
            <div class="image-gallery-header">
                <div class="gallery-title">
                    <span>{{ $galleryProject->project_title }}</span>
                    <span class="image-counter" id="imageCounter">Image 1 of {{ count($galleryProject->photos) + 1 }}</span>
                </div>
                <button type="button" class="gallery-close-btn" wire:click="closeImageGallery">
                    <span>&times;</span>
                </button>
            </div>

            <!-- Main Image Area -->
            <div class="image-gallery-main">
                <!-- Navigation Arrows -->
                @if((count($galleryProject->photos) + 1) > 1)
                <button class="gallery-nav-btn gallery-nav-prev" id="prevBtn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <button class="gallery-nav-btn gallery-nav-next" id="nextBtn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
                @endif

                <!-- Large Center Image -->
                <div class="gallery-main-image-wrapper">
                    <img id="mainGalleryImage" 
                         src="{{ $galleryProject->icon_image ? asset('storage/' . $galleryProject->icon_image) : asset('img/causes/1.png') }}" 
                         alt="{{ $galleryProject->project_title }}"
                         class="gallery-main-image">
                    
                    <!-- Project Description Overlay -->
                    <div class="gallery-description-overlay">
                        <p class="gallery-description-text">{{ $galleryProject->project_description }}</p>
                    </div>
                </div>
            </div>

            <!-- Thumbnail Navigation -->
            <div class="image-gallery-thumbnails">
                <!-- Icon Image Thumbnail -->
                <div class="gallery-thumbnail active" data-image-index="0" data-image-src="{{ $galleryProject->icon_image ? asset('storage/' . $galleryProject->icon_image) : asset('img/causes/1.png') }}">
                    <img src="{{ $galleryProject->icon_image ? asset('storage/' . $galleryProject->icon_image) : asset('img/causes/1.png') }}" alt="Main">
                </div>
                <!-- Project Photos Thumbnails -->
                @foreach($galleryProject->photos as $index => $photo)
                <div class="gallery-thumbnail" 
                     data-image-index="{{ $index + 1 }}" 
                     data-image-src="{{ asset('storage/' . $photo->body_image) }}">
                    <img src="{{ asset('storage/' . $photo->body_image) }}" alt="Photo {{ $index + 1 }}">
                </div>
                @endforeach
            </div>
        </div>
    </div>
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
                        Livewire.dispatch('payment-success', { reference: response.reference });
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

            // Image Gallery Navigation
            let currentImageIndex = 0;
            let galleryImages = [];
            let galleryInitialized = false;
            let keyDownHandler = null;

            function initImageGallery() {
                if (galleryInitialized) return;
                const modal = document.getElementById('imageGalleryModal');
                if (!modal || modal.style.display === 'none') return;

                const thumbnails = modal.querySelectorAll('.gallery-thumbnail');
                const mainImage = document.getElementById('mainGalleryImage');
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');
                const imageCounter = document.getElementById('imageCounter');

                if (!mainImage) return;

                galleryImages = Array.from(thumbnails).map(thumb => ({
                    src: thumb.dataset.imageSrc,
                    index: parseInt(thumb.dataset.imageIndex)
                }));

                if (galleryImages.length === 0) {
                    const mainImgSrc = mainImage.src;
                    galleryImages = [{ src: mainImgSrc, index: 0 }];
                }

                currentImageIndex = 0;
                updateMainImage();

                thumbnails.forEach((thumb, index) => {
                    thumb.addEventListener('click', () => {
                        currentImageIndex = index;
                        updateMainImage();
                    });
                });

                if (prevBtn) {
                    prevBtn.onclick = () => {
                        currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
                        updateMainImage();
                    };
                }

                if (nextBtn) {
                    nextBtn.onclick = () => {
                        currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
                        updateMainImage();
                    };
                }

                keyDownHandler = (e) => {
                    if (!modal || modal.style.display === 'none') return;
                    
                    if (e.key === 'ArrowLeft') {
                        e.preventDefault();
                        currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
                        updateMainImage();
                    } else if (e.key === 'ArrowRight') {
                        e.preventDefault();
                        currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
                        updateMainImage();
                    } else if (e.key === 'Escape') {
                        e.preventDefault();
                        @this.closeImageGallery();
                        galleryInitialized = false;
                        if (keyDownHandler) {
                            document.removeEventListener('keydown', keyDownHandler);
                            keyDownHandler = null;
                        }
                    }
                };

                document.addEventListener('keydown', keyDownHandler);
                galleryInitialized = true;

                function updateMainImage() {
                    if (galleryImages.length === 0) return;

                    const currentImage = galleryImages[currentImageIndex];
                    if (mainImage && currentImage) {
                        mainImage.src = currentImage.src;
                    }

                    thumbnails.forEach((thumb, index) => {
                        thumb.classList.toggle('active', index === currentImageIndex);
                    });

                    if (imageCounter) {
                        imageCounter.textContent = `Image ${currentImageIndex + 1} of ${galleryImages.length}`;
                    }
                }
            }

            Livewire.hook('morph.updated', ({ el }) => {
                const modal = document.getElementById('imageGalleryModal');
                if (!modal || modal.style.display === 'none') {
                    galleryInitialized = false;
                    if (keyDownHandler) {
                        document.removeEventListener('keydown', keyDownHandler);
                        keyDownHandler = null;
                    }
                } else if (modal.style.display === 'block' && !galleryInitialized) {
                    setTimeout(() => {
                        galleryInitialized = false;
                        initImageGallery();
                    }, 150);
                }
            });
        });
    </script>

    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }

        /* Card Hover Effects */
        .single_cause:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }

        .single_cause:hover .thumb img {
            transform: scale(1.08);
        }

        /* Radio Button Styling */
        input[type="radio"]:checked + label {
            background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%) !important;
            color: white !important;
            border-color: #10b981 !important;
        }

        /* Button Hover Effects */
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16,185,129,0.4) !important;
        }

        /* Image Gallery Modal Styles */
        .image-gallery-modal {
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

        .image-gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            cursor: pointer;
        }

        .image-gallery-container {
            position: relative;
            width: 95%;
            max-width: 1400px;
            height: 95vh;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            z-index: 10001;
        }

        .image-gallery-header {
            background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            z-index: 10002;
        }

        .gallery-title {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .gallery-title span:first-child {
            font-weight: 600;
            font-size: 18px;
        }

        .image-counter {
            font-size: 14px;
            opacity: 0.9;
        }

        .gallery-close-btn {
            background: transparent;
            border: none;
            color: white;
            font-size: 32px;
            cursor: pointer;
            padding: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.3s ease;
        }

        .gallery-close-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .image-gallery-main {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
            overflow: hidden;
        }

        .gallery-main-image-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gallery-main-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: opacity 0.3s ease;
        }

        .gallery-description-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.5) 70%, transparent 100%);
            padding: 40px 30px 30px;
            color: white;
        }

        .gallery-description-text {
            margin: 0;
            font-size: 16px;
            line-height: 1.6;
            max-height: 120px;
            overflow-y: auto;
        }

        .gallery-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10003;
            transition: all 0.3s ease;
            color: #333;
        }

        .gallery-nav-btn:hover {
            background: white;
            transform: translateY(-50%) scale(1.1);
        }

        .gallery-nav-prev {
            left: 20px;
        }

        .gallery-nav-next {
            right: 20px;
        }

        .image-gallery-thumbnails {
            display: flex;
            gap: 10px;
            padding: 15px 20px;
            background: rgba(0, 0, 0, 0.8);
            overflow-x: auto;
            justify-content: center;
            align-items: center;
        }

        .gallery-thumbnail {
            flex-shrink: 0;
            width: 80px;
            height: 80px;
            border-radius: 6px;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s ease;
            opacity: 0.6;
        }

        .gallery-thumbnail:hover {
            opacity: 0.9;
            transform: scale(1.05);
        }

        .gallery-thumbnail.active {
            border-color: #10b981;
            opacity: 1;
            transform: scale(1.1);
        }

        .gallery-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        @media (max-width: 768px) {
            .image-gallery-container {
                width: 100%;
                height: 100vh;
                border-radius: 0;
            }

            .gallery-nav-btn {
                width: 40px;
                height: 40px;
            }

            .gallery-nav-prev {
                left: 10px;
            }

            .gallery-nav-next {
                right: 10px;
            }

            .gallery-thumbnail {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</div>
