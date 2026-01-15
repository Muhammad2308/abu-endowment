<div class="popular_causes_area section_padding" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 50%, #e8f5f1 100%);">
    <div class="container">
        <!-- Header Section -->
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="section_title text-center mb-5">
                    <h3 style="color: #227722; font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem;">
                        Our Projects
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
            <div class="col-lg-3 col-md-6 mb-4">
                @php
                    $raised = floatval($project->raised ?? 0);
                    $target = floatval($project->target ?? 0);
                    $percentage = ($target > 0) ? round(($raised / $target) * 100, 1) : 0;
                @endphp
                <div class="project-card" style="background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.08); border: 1px solid #f0f0f0; height: 100%; display: flex; flex-direction: column; transition: transform 0.3s ease;">
                    <!-- Image Area -->
                    <div class="project-thumb" style="position: relative; height: 220px; overflow: hidden;">
                        <img src="{{ $project->icon_image ? asset('storage/' . $project->icon_image) : asset('img/causes/1.png') }}" 
                             alt="{{ $project->project_title }}" 
                             style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                        
                        <!-- Category Badge -->
                        <span style="position: absolute; top: 15px; left: 15px; background: rgba(255,255,255,0.9); padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; color: #333; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            {{ $project->category->name ?? 'General' }}
                        </span>
                        
                        <!-- Percentage Badge -->
                        <span style="position: absolute; top: 15px; right: 15px; background: #227722; color: white; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            {{ $percentage }}%
                        </span>

                        <!-- Bottom Actions Overlay (Hidden by default, shown on hover) -->
                        <div class="card-actions-overlay" style="position: absolute; bottom: 15px; left: 15px; right: 15px; display: flex; justify-content: space-between; align-items: center; opacity: 0; transform: translateY(10px); transition: all 0.3s ease;">
                            <button wire:click.prevent="openImageGallery({{ $project->id }})" style="background: rgba(255,255,255,0.95); border: none; padding: 6px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; color: #333; display: flex; align-items: center; gap: 6px; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <i class="fa fa-eye"></i> View Gallery
                            </button>
                            <button style="background: rgba(255,255,255,0.95); border: none; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #333; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <i class="fa fa-heart"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="project-content" style="padding: 20px; flex: 1; display: flex; flex-direction: column;">
                        <h4 style="font-size: 1.1rem; font-weight: 700; color: #227722; margin-bottom: 10px; line-height: 1.4;">
                            <a href="{{ route('project.single', $project->id) }}" style="color: inherit; text-decoration: none;">
                                {{ $project->project_title }}
                            </a>
                        </h4>
                        
                        <p style="color: #6b7280; font-size: 0.9rem; line-height: 1.6; margin-bottom: 20px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $project->project_description }}
                        </p>
                        
                        <div style="margin-top: auto;">
                            <!-- Progress Bar -->
                            <div class="progress mb-2" style="height: 6px; border-radius: 3px; background-color: #e5e7eb; overflow: visible;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ min($percentage, 100) }}%; background-color: #227722; border-radius: 3px; position: relative;" 
                                     aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                     @if($percentage > 0)
                                     <span style="position: absolute; right: -3px; top: -2px; width: 10px; height: 10px; background: #227722; border-radius: 50%; border: 2px solid white;"></span>
                                     @endif
                                </div>
                            </div>
                            
                            <!-- Stats -->
                            <div class="d-flex justify-content-between align-items-center mb-3" style="font-size: 0.8rem;">
                                <div>
                                    <span class="text-muted d-block" style="font-size: 0.75rem;">Raised</span>
                                    <span style="color: #227722; font-weight: 700;">₦{{ number_format($project->raised ?? 0, 2) }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-muted d-block" style="font-size: 0.75rem;">Goal</span>
                                    <span style="color: #374151; font-weight: 700;">₦{{ number_format($project->target ?? 0, 2) }}</span>
                                </div>
                            </div>
                            
                            <!-- Buttons -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <button wire:click="openDonationModal({{ $project->id }})" 
                                        class="btn" 
                                        style="background-color: #227722; color: white; font-weight: 600; padding: 10px 24px; border-radius: 50px; border: none; transition: all 0.3s ease; font-size: 0.9rem; display: flex; align-items: center; box-shadow: 0 4px 6px rgba(34, 119, 34, 0.2);">
                                    <i class="fas fa-heart mr-2"></i> Donate Now
                                </button>
                                
                                <a href="{{ route('project.single', $project->id) }}" style="font-size: 0.9rem; color: #6b7280; text-decoration: none; font-weight: 600; display: flex; align-items: center; transition: color 0.2s;">
                                    Read More <i class="fa fa-angle-right ml-1"></i>
                                </a>
                            </div>
                        </div>
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

    </div>

    <!-- Donation Modal -->
    @if($showModal && $selectedProject)
    <div class="modal fade show d-block" id="donationModal" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 24px; border: none; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); position: relative;">
                
                <!-- Decorative Top Border -->
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 6px; background: linear-gradient(90deg, #227722, #1a5c1a);"></div>

                <!-- Close Button -->
                <button type="button" class="close" wire:click="closeModal" style="position: absolute; top: 20px; right: 20px; opacity: 0.5; z-index: 10; font-size: 1.5rem; transition: opacity 0.2s;">
                    <span aria-hidden="true">&times;</span>
                </button>

                <!-- Modal Body -->
                <div class="modal-body px-5 pb-5 pt-5">
                    <div class="text-center mb-4">
                        <h5 class="modal-title font-weight-bold" style="color: #111827; font-size: 1.5rem; font-family: 'Merriweather', serif;">Donate to {{ $selectedProject->project_title }}</h5>
                        <p class="text-muted small mt-1" style="font-family: 'Inter', sans-serif;">Your contribution makes a difference</p>
                    </div>

                    <form wire:submit.prevent="donate">
                        <!-- Email Input -->
                        <div class="form-group mb-4">
                            <label class="font-weight-bold mb-2" style="color: #374151; font-size: 0.95rem; font-family: 'Merriweather', serif;">Email Address</label>
                            <div class="input-group" style="background: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb; transition: all 0.3s ease;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text border-0 bg-transparent pl-3">
                                        <i class="fa fa-envelope" style="color: #9ca3af;"></i>
                                    </span>
                                </div>
                                <input type="email" wire:model="email" class="form-control border-0 bg-transparent" placeholder="Enter your email address" required style="height: 50px; padding-left: 10px; color: #1f2937; font-weight: 500; font-family: 'Inter', sans-serif;">
                            </div>
                            @error('email') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Amount Selection -->
                        <div class="form-group mb-4">
                            <label class="font-weight-bold mb-3" style="color: #374151; font-size: 0.95rem; font-family: 'Merriweather', serif;">Select Donation Amount</label>
                            <div class="amount-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 12px;">
                                
                                <label class="amount-option" style="cursor: pointer;">
                                    <input type="radio" wire:model.live="selectedAmount" value="1000" class="d-none">
                                    <div class="amount-card {{ $selectedAmount == 1000 ? 'active' : '' }}">
                                        <span class="amount-value" style="font-family: 'IBM Plex Mono', monospace;">₦1k</span>
                                    </div>
                                </label>

                                <label class="amount-option" style="cursor: pointer;">
                                    <input type="radio" wire:model.live="selectedAmount" value="5000" class="d-none">
                                    <div class="amount-card {{ $selectedAmount == 5000 ? 'active' : '' }}">
                                        <span class="amount-value" style="font-family: 'IBM Plex Mono', monospace;">₦5k</span>
                                    </div>
                                </label>

                                <label class="amount-option" style="cursor: pointer;">
                                    <input type="radio" wire:model.live="selectedAmount" value="10000" class="d-none">
                                    <div class="amount-card {{ $selectedAmount == 10000 ? 'active' : '' }}">
                                        <span class="amount-value" style="font-family: 'IBM Plex Mono', monospace;">₦10k</span>
                                    </div>
                                </label>

                                <label class="amount-option" style="cursor: pointer;">
                                    <input type="radio" wire:model.live="selectedAmount" value="custom" class="d-none">
                                    <div class="amount-card {{ $selectedAmount == 'custom' ? 'active' : '' }}">
                                        <span class="amount-value" style="font-family: 'IBM Plex Mono', monospace;">Custom</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Custom Amount Input (Conditional) -->
                        <div class="form-group mb-4" x-data="{ show: @entangle('selectedAmount') }" x-show="show == 'custom'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                            <label class="font-weight-bold mb-2" style="color: #374151; font-size: 0.95rem; font-family: 'Merriweather', serif;">Enter Custom Amount</label>
                            <div class="input-group" style="background: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb; transition: all 0.3s ease;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text border-0 bg-transparent pl-3 font-weight-bold" style="color: #227722;">₦</span>
                                </div>
                                <input type="number" wire:model.live="customAmount" class="form-control border-0 bg-transparent" placeholder="e.g. 25000" style="height: 50px; padding-left: 5px; color: #1f2937; font-weight: 600; font-size: 1.1rem; font-family: 'IBM Plex Mono', monospace;">
                            </div>
                            @error('amount') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="mt-5">
                            <button type="submit" class="btn btn-block donate-btn" style="background: linear-gradient(135deg, #227722 0%, #1a5c1a 100%); color: white; font-weight: 700; padding: 16px; border-radius: 14px; border: none; font-size: 1.1rem; letter-spacing: 0.5px; box-shadow: 0 10px 20px rgba(34, 119, 34, 0.25); transition: all 0.3s ease; width: 100%; font-family: 'Merriweather', serif;">
                                Donate Now <span wire:loading class="spinner-border spinner-border-sm ml-2"></span>
                            </button>
                            <p class="text-center mt-3 text-muted small" style="font-family: 'Inter', sans-serif;">
                                <i class="fa fa-lock mr-1"></i> Secure payment powered by Paystack
                            </p>
                        </div>
                    </form>
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
            
            .close:hover {
                opacity: 1 !important;
                color: #111827 !important;
            }
        </style>
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
                        <h3 class="mb-0" style="font-weight: 700; font-size: 1.5rem; color: #227722;">{{ $galleryProject->project_title }}</h3>
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
        .project-card:hover {
            transform: translateY(-5px) scale(1.02) !important;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        }
        
        .project-card:hover .card-actions-overlay {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
        
        .project-card:hover .project-thumb img {
            transform: scale(1.1);
        }

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
