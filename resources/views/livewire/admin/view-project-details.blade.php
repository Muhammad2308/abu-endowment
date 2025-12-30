<div x-data="{ 
    show: false, 
    activeIndex: 0,
    showDesc: true,
    photos: [
        @foreach($project->photos as $photo)
        {
            url: '{{ $photo->image_url }}',
            description: '{{ addslashes($photo->description) }}',
            title: '{{ addslashes($photo->title) }}'
        },
        @endforeach
    ]
}" @open-view-project-modal.window="show = true">
    
    <button @click="show = true" 
            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-sm" 
            title="View Details">
        <i class="fas fa-eye mr-1"></i> View
    </button>
    
    <!-- Modal Overlay -->
    <div x-show="show" 
         x-cloak 
         class="fixed inset-0 z-[60] overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="show = false"></div>

        <!-- Modal Content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative w-full max-w-5xl bg-[#0f172a] rounded-3xl shadow-2xl border border-gray-800 overflow-hidden"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-800/50">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden border border-gray-700 shadow-inner">
                            @if($project->icon_image)
                                <img src="{{ $project->icon_image_url ?? asset('storage/' . $project->icon_image) }}" class="w-full h-full object-cover" alt="Icon">
                            @else
                                <div class="w-full h-full bg-gray-800 flex items-center justify-center text-gray-500">
                                    <i class="fas fa-project-diagram"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white tracking-tight">{{ $project->project_title }}</h3>
                            <p class="text-gray-400 text-sm font-medium">{{ count($project->photos) }} Photos</p>
                        </div>
                    </div>
                    <button @click="show = false" class="w-12 h-12 flex items-center justify-center rounded-xl border border-gray-700 text-gray-400 hover:text-white hover:border-gray-500 transition-all duration-200 bg-gray-800/50">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Left Column: Image Viewer -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Main Image Display -->
                        <div class="relative aspect-video rounded-3xl overflow-hidden border border-gray-800 bg-black/40 group">
                            <template x-if="photos.length > 0">
                                <img :src="photos[activeIndex].url" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" 
                                     :alt="photos[activeIndex].title">
                            </template>
                            <template x-if="photos.length === 0">
                                <div class="w-full h-full flex items-center justify-center text-gray-600">
                                    <div class="text-center">
                                        <i class="fas fa-images text-6xl mb-4 opacity-20"></i>
                                        <p>No photos available</p>
                                    </div>
                                </div>
                            </template>

                            <!-- Overlays -->
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1.5 bg-black/60 backdrop-blur-md text-white text-xs font-bold rounded-full border border-white/10" x-text="(activeIndex + 1) + ' / ' + photos.length"></span>
                            </div>
                            <button class="absolute top-4 right-4 px-4 py-2 bg-black/60 backdrop-blur-md text-white text-xs font-bold rounded-xl border border-white/10 hover:bg-white/20 transition-colors flex items-center space-x-2">
                                <i class="fas fa-search-plus"></i>
                                <span>Expand</span>
                            </button>
                        </div>

                        <!-- Photo Description Box -->
                        <template x-if="photos.length > 0 && (photos[activeIndex].title || photos[activeIndex].description)">
                            <div class="relative p-6 bg-blue-900/20 border border-blue-500/30 rounded-3xl backdrop-blur-sm" x-show="showDesc">
                                <button @click="showDesc = false" class="absolute top-4 right-4 text-gray-500 hover:text-gray-300 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                                <h4 class="text-lg font-bold text-white mb-2" x-text="photos[activeIndex].title || 'Photo Details'"></h4>
                                <p class="text-gray-300 text-sm leading-relaxed" x-text="photos[activeIndex].description"></p>
                            </div>
                        </template>

                        <!-- Thumbnail Strip -->
                        <div class="flex space-x-3 overflow-x-auto pb-2 scrollbar-hide">
                            <template x-for="(photo, index) in photos" :key="index">
                                <button @click="activeIndex = index; showDesc = true" 
                                        class="relative flex-shrink-0 w-24 h-24 rounded-2xl overflow-hidden border-2 transition-all duration-200"
                                        :class="activeIndex === index ? 'border-blue-500 scale-95 shadow-lg shadow-blue-500/20' : 'border-transparent opacity-50 hover:opacity-100'">
                                    <img :src="photo.url" class="w-full h-full object-cover" alt="Thumbnail">
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Right Column: Sidebar -->
                    <div class="space-y-6">
                        <!-- Funding Status -->
                        <div class="p-6 bg-blue-900/20 border border-blue-500/30 rounded-3xl space-y-4">
                            <h4 class="text-xl font-bold text-white">Funding Status</h4>
                            <div class="h-1 w-12 bg-blue-500 rounded-full"></div>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-gray-400 text-xs uppercase font-bold tracking-wider mb-1">Raised</p>
                                        <p class="text-2xl font-black text-blue-400">₦{{ number_format($project->raised, 2) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-gray-400 text-xs uppercase font-bold tracking-wider mb-1">Target</p>
                                        <p class="text-lg font-bold text-gray-300">₦{{ number_format($project->target, 2) }}</p>
                                    </div>
                                </div>

                                <div class="relative h-4 bg-gray-800 rounded-full overflow-hidden border border-gray-700">
                                    <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-blue-600 to-blue-400 rounded-full transition-all duration-1000 shadow-[0_0_20px_rgba(59,130,246,0.5)]"
                                         style="width: {{ $project->target > 0 ? min(($project->raised / $project->target) * 100, 100) : 0 }}%">
                                    </div>
                                </div>

                                <div class="flex justify-between text-xs font-bold">
                                    <span class="text-blue-400">{{ $project->target > 0 ? round(($project->raised / $project->target) * 100, 1) : 0 }}% Funded</span>
                                    <span class="text-gray-500">₦{{ number_format(max(0, $project->target - $project->raised), 2) }} Remaining</span>
                                </div>
                            </div>
                        </div>

                        <!-- About Section -->
                        <div class="p-6 bg-gray-800/30 border border-gray-700/50 rounded-3xl space-y-4">
                            <h4 class="text-xl font-bold text-white">About This Project</h4>
                            <div class="h-1 w-12 bg-blue-500 rounded-full"></div>
                            <p class="text-gray-400 text-sm leading-relaxed">
                                {{ $project->project_description }}
                            </p>
                        </div>

                        <!-- Quick Info / All Photos Section -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-bold text-white px-2">All Photos</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <template x-for="(photo, index) in photos" :key="'grid-'+index">
                                    <button @click="activeIndex = index; showDesc = true" 
                                            class="aspect-square rounded-2xl overflow-hidden border border-gray-800 hover:border-blue-500/50 transition-colors">
                                        <img :src="photo.url" class="w-full h-full object-cover" alt="Grid Photo">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="p-6 border-t border-gray-800/50 flex justify-end">
                    <button @click="show = false" class="px-8 py-3 bg-gray-800 hover:bg-gray-700 text-white font-bold rounded-2xl transition-all duration-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>