<div x-data="{ show: false }">
    <button
    @click="show = true" 
    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" title="View Details">
        <i class="fas fa-eye mr-1"></i> View
    </button>
    
    <div x-show="show" 
         x-cloak 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center" @click.away="show = false">
        <div 
        class="relative p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Project Details
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" @click="show = false; $wire.closeModal()">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mt-4 space-y-4">
                <div x-data="{ selected: '{{ $project?->icon_image_url ?? '' }}' }">
                    <!-- Main Image -->
                    <div class="flex justify-center mb-4">
                        <img :src="selected" alt="Selected Image" class="w-64 h-64 rounded-lg object-cover border" />
                    </div>
                    <!-- Thumbnails -->
                    <div class="flex flex-wrap gap-2 justify-center mb-4">
                        <!-- Project Icon as first thumbnail -->
                        <img src="{{ $project?->icon_image_url ?? '' }}" alt="Icon" class="w-16 h-16 rounded-lg object-cover border cursor-pointer"
                            :class="{ 'ring-2 ring-blue-500': selected === '{{ $project?->icon_image_url ?? '' }}' }"
                            @click="selected = '{{ $project?->icon_image_url ?? '' }}'" />
                        <!-- Project Photos -->
                        @foreach ($project?->photos ?? [] as $photo)
                            <img src="{{ asset('storage/' . $photo->body_image) }}" alt="Photo" class="w-16 h-16 rounded-lg object-cover border cursor-pointer"
                                :class="{ 'ring-2 ring-blue-500': selected === '{{ asset('storage/' . $photo->body_image) }}' }"
                                @click="selected = '{{ asset('storage/' . $photo->body_image) }}'" />
                        @endforeach
                    </div>
                    <!-- Project Description -->
                    <div>
                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $project?->project_title ?? '' }}</h4>

                        <p class="mt-2 text-base text-gray-600 dark:text-gray-400">
                            {{ $project?->project_description ?? '' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end items-center pt-4 border-t border-gray-200 dark:border-gray-700 mt-4">
                <button type="button" 
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none"
                        @click="show = false">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>