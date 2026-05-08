<div class="popular_causes_area section_padding" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 50%, #e8f5f1 100%);">
    <div class="container">
        <!-- Header Section -->
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="section_title text-center mb-5">
                    <h3 style="color: #227722; font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem;">
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
            <div class="col-lg-3 col-md-6 mb-4">
                @php
                    $raised = floatval($project->raised ?? 0);
                    $target = floatval($project->target ?? 0);
                    $percentage = ($target > 0) ? round(($raised / $target) * 100, 1) : 0;
                @endphp
                <div class="project-card-home" style="background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: none; height: 100%; display: flex; flex-direction: column; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
                    <!-- Image Area -->
                    <div class="project-thumb" style="position: relative; height: 240px; overflow: hidden;">
                        <img src="{{ $project->icon_image ? asset('storage/' . $project->icon_image) : asset('img/causes/1.png') }}" 
                             alt="{{ $project->project_title }}" 
                             style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease;">
                        
                        <!-- Overlay Gradient -->
                        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 50%);"></div>

                        <!-- Category Badge -->
                        <span style="position: absolute; top: 15px; left: 15px; background: rgba(255,255,255,0.95); padding: 5px 14px; border-radius: 30px; font-size: 0.7rem; font-weight: 700; color: #227722; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                            {{ $project->category->name ?? 'General' }}
                        </span>
                        
                        <!-- Percentage Badge (Circular) -->
                        <div style="position: absolute; top: 15px; right: 15px; width: 45px; height: 45px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border: 3px solid #227722;">
                            <span style="font-size: 0.75rem; font-weight: 800; color: #227722;">{{ intval($percentage) }}%</span>
                        </div>

                        <!-- Bottom Actions Overlay -->
                        <div class="card-actions-overlay" style="position: absolute; bottom: 20px; left: 0; right: 0; display: flex; justify-content: center; gap: 15px; opacity: 0; transform: translateY(20px); transition: all 0.3s ease;">
                            <button wire:click.prevent="openImageGallery({{ $project->id }})" style="background: #fff; border: none; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #227722; cursor: pointer; box-shadow: 0 5px 15px rgba(0,0,0,0.2); transition: transform 0.2s;">
                                <i class="fa fa-eye"></i>
                            </button>
                            <button style="background: #227722; border: none; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; cursor: pointer; box-shadow: 0 5px 15px rgba(34, 119, 34, 0.4); transition: transform 0.2s;">
                                <i class="fa fa-heart"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="project-content" style="padding: 25px; flex: 1; display: flex; flex-direction: column; position: relative;">
                        <h4 style="font-size: 1.2rem; font-weight: 800; color: #1f2937; margin-bottom: 12px; line-height: 1.4; font-family: 'Playfair Display', serif;">
                            <a href="{{ route('project.single', $project->id) }}" style="color: inherit; text-decoration: none; transition: color 0.3s;">
                                {{ $project->project_title }}
                            </a>
                        </h4>
                        
                        <p style="color: #6b7280; font-size: 0.9rem; line-height: 1.6; margin-bottom: 20px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $project->project_description }}
                        </p>
                        
                        <div style="margin-top: auto;">
                            <!-- Progress Bar (Thicker, Rounded) -->
                            <div class="progress mb-3" style="height: 10px; border-radius: 10px; background-color: #f3f4f6; overflow: hidden; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ min($percentage, 100) }}%; background: linear-gradient(90deg, #227722, #1a5c1a); border-radius: 10px;" 
                                     aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            
                            <!-- Stats -->
                            <div class="d-flex justify-content-between align-items-center mb-4" style="font-size: 0.85rem;">
                                <div>
                                    <span class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Raised</span>
                                    <span style="color: #227722; font-weight: 800; font-size: 1rem;">₦{{ number_format($project->raised ?? 0, 2) }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Goal</span>
                                    <span style="color: #4b5563; font-weight: 700;">₦{{ number_format($project->target ?? 0, 2) }}</span>
                                </div>
                            </div>
                            
                            <!-- Buttons -->
                            <div class="d-flex justify-content-between align-items-center">
                                <button wire:click="openDonationModal({{ $project->id }})" 
                                        class="btn btn-donate-home" 
                                        style="background-color: #227722; color: white; font-weight: 600; padding: 12px 24px; border-radius: 12px; border: none; transition: all 0.3s ease; font-size: 0.9rem; display: flex; align-items: center; box-shadow: 0 4px 12px rgba(34, 119, 34, 0.25);">
                                    Donate Now
                                </button>
                                
                                <a href="{{ route('project.single', $project->id) }}" style="width: 45px; height: 45px; border-radius: 12px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #4b5563; transition: all 0.3s ease; text-decoration: none;">
                                    <i class="fa fa-arrow-right"></i>
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

        <!-- See All Projects Link -->
        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="{{ route('projects') }}" class="btn-see-all" style="display: inline-flex; align-items: center; gap: 10px; padding: 12px 35px; background: transparent; border: 2px solid #227722; color: #227722; font-weight: 700; border-radius: 50px; transition: all 0.3s ease; text-decoration: none;">
                    See All Projects <i class="fas fa-long-arrow-alt-right"></i>
                </a>
            </div>
        </div>

        <style>
            .project-card-home:hover {
                transform: translateY(-10px) !important;
                box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
            }
            .project-card-home:hover .project-thumb img {
                transform: scale(1.1);
            }
            .project-card-home:hover .card-actions-overlay {
                opacity: 1 !important;
                transform: translateY(0) !important;
            }
            .project-card-home:hover h4 a {
                color: #227722 !important;
            }
            .btn-donate-home:hover {
                background-color: #227722 !important;
                transform: translateY(-2px);
                box-shadow: 0 8px 15px rgba(34, 119, 34, 0.3) !important;
            }
            .btn-see-all:hover {
                background: #227722 !important;
                color: #fff !important;
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(34, 119, 34, 0.2);
            }
        </style>
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

                        <!-- Donation Amount -->
                        <div class="form-group mb-4">
                            <label class="font-weight-bold mb-2" style="color: #374151; font-size: 0.95rem; font-family: 'Merriweather', serif;">Donation Amount</label>
                            <div class="input-group" style="background: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb; transition: all 0.3s ease;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text border-0 bg-transparent pl-3 font-weight-bold" style="color: #227722;">₦</span>
                                </div>
                                <input type="number" wire:model="amount" class="form-control border-0 bg-transparent" placeholder="Enter amount (e.g. 5000)" required min="100" style="height: 50px; padding-left: 5px; color: #1f2937; font-weight: 600; font-size: 1.1rem; font-family: 'IBM Plex Mono', monospace;">
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
                        <h3 class="mb-0" style="font-weight: 800; font-size: 1.4rem; color: #1f2937; letter-spacing: -0.5px;">{{ $galleryProject->project_title }}</h3>
                        <span class="text-muted" style="font-size: 0.85rem; font-weight: 500;">
                            <i class="fa fa-camera mr-1 text-success"></i> {{ count($galleryProject->photos) + 1 }} Photos
                        </span>
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
                        <div class="main-image-area mb-3 position-relative shadow-sm">
                            <template x-if="photos.length > 0">
                                <img :src="photos[activeIndex].url" class="main-image" :alt="photos[activeIndex].title">
                            </template>
                            
                            <!-- Image Counter -->
                            <div class="image-counter-badge">
                                <span x-text="(activeIndex + 1) + ' / ' + photos.length"></span>
                            </div>

                            <!-- Navigation Arrows -->
                            <button class="nav-arrow prev" @click="activeIndex = (activeIndex > 0) ? activeIndex - 1 : photos.length - 1">
                                <i class="fa fa-chevron-left"></i>
                            </button>
                            <button class="nav-arrow next" @click="activeIndex = (activeIndex < photos.length - 1) ? activeIndex + 1 : 0">
                                <i class="fa fa-chevron-right"></i>
                            </button>

                            <!-- Description Overlay -->
                            <div class="image-desc-overlay" x-show="showDesc && (photos[activeIndex].title || photos[activeIndex].description)" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform translate-y-4"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform translate-y-4">
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
                        <div class="project-info-sidebar h-100">
                            <!-- Funding Status -->
                            <div class="info-card mb-4 bg-white border-0 shadow-sm">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h5 class="card-title mb-0 border-0 p-0">Funding Status</h5>
                                    <span class="badge badge-soft-success px-3 py-2 rounded-pill">Active</span>
                                </div>
                                
                                <div class="progress-wrapper mb-4">
                                    @php
                                        $raised = floatval($galleryProject->raised ?? 0);
                                        $target = floatval($galleryProject->target ?? 0);
                                        $percentage = ($target > 0) ? round(($raised / $target) * 100, 1) : 0;
                                    @endphp
                                    
                                    <div class="d-flex justify-content-between mb-2 align-items-end">
                                        <div>
                                            <span class="text-muted small text-uppercase font-weight-bold d-block mb-1">Raised</span>
                                            <span class="h4 mb-0 text-dark font-weight-bold">₦{{ number_format($raised) }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-muted small text-uppercase font-weight-bold d-block mb-1">Goal</span>
                                            <span class="h6 mb-0 text-muted">₦{{ number_format($target) }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="progress mb-2" style="height: 8px; border-radius: 4px; background-color: #f3f4f6;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ min($percentage, 100) }}%; background: linear-gradient(90deg, #227722, #1a5c1a); border-radius: 4px;">
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mt-2 small">
                                        <span class="text-success font-weight-bold">{{ $percentage }}% Funded</span>
                                        <span class="text-muted">₦{{ number_format(max(0, $target - $raised)) }} to go</span>
                                    </div>
                                </div>

                                <button wire:click="openDonationModal({{ $galleryProject->id }})" 
                                        class="btn btn-block py-3 font-weight-bold text-white shadow-sm hover-lift"
                                        style="background: #227722; border-radius: 12px; transition: all 0.3s;">
                                    Donate Now <i class="fa fa-heart ml-2 text-white-50"></i>
                                </button>
                            </div>

                            <!-- About Project -->
                            <div class="info-card bg-white border-0 shadow-sm flex-fill">
                                <h5 class="card-title mb-3 border-0 p-0">About Project</h5>
                                <div class="description-text custom-scrollbar" style="max-height: 400px; overflow-y: auto; padding-right: 10px;">
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
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            cursor: pointer;
        }

        .project-details-container {
            position: relative;
            width: 95%;
            max-width: 1200px;
            height: 90vh;
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            z-index: 10001;
            animation: modalSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid rgba(0,0,0,0.05);
        }

        @keyframes modalSlideUp {
            from { transform: translateY(40px) scale(0.95); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }

        .project-details-header {
            padding: 20px 30px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
        }

        .project-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #f3f4f6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .project-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .close-btn {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: #6b7280;
        }

        .close-btn:hover {
            background: #f3f4f6;
            color: #1f2937;
            transform: rotate(90deg);
        }

        .project-details-body {
            flex: 1;
            overflow-y: auto;
            padding: 30px;
            background: #fcfcfc;
        }

        /* Image Viewer Styles */
        .main-image-area {
            width: 100%;
            height: 500px;
            background: #f8f9fa;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #f3f4f6;
        }

        .main-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.9);
            border: 1px solid rgba(0,0,0,0.05);
            color: #1f2937;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            opacity: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .main-image-area:hover .nav-arrow {
            opacity: 1;
        }

        .nav-arrow.prev { left: 20px; }
        .nav-arrow.next { right: 20px; }
        .nav-arrow:hover { background: #fff; transform: translateY(-50%) scale(1.1); }

        .image-counter-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255,255,255,0.9);
            color: #1f2937;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 700;
            backdrop-filter: blur(4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .image-desc-overlay {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.5);
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
            margin-bottom: 6px;
            font-weight: 700;
            color: #111827;
            font-size: 1.1rem;
        }

        .image-desc-overlay p {
            margin-bottom: 0;
            font-size: 0.95rem;
            color: #4b5563;
            line-height: 1.5;
        }

        .thumbnails-strip {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding: 4px;
        }

        .thumbnail-item {
            width: 70px;
            height: 70px;
            flex-shrink: 0;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s;
            opacity: 0.6;
            background: #f3f4f6;
        }

        .thumbnail-item:hover {
            opacity: 1;
            transform: translateY(-2px);
        }

        .thumbnail-item.active {
            border-color: #227722;
            opacity: 1;
            box-shadow: 0 4px 12px rgba(34, 119, 34, 0.15);
        }

        .thumbnail-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Sidebar Styles */
        .info-card {
            background: #fff;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid #f3f4f6;
            margin-bottom: 20px;
        }

        .card-title {
            font-weight: 800;
            color: #111827;
            font-size: 1.1rem;
            letter-spacing: -0.3px;
        }

        .badge-soft-success {
            background-color: #ecfdf5;
            color: #227722;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .description-text {
            color: #4b5563;
            line-height: 1.8;
            font-size: 0.95rem;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(34, 119, 34, 0.2);
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
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
