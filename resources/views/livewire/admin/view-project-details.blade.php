<div>
    @if ($showModal && $project)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Project Details
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" wire:click="closeModal">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mt-4 space-y-4">
                @if ($project->icon_image)
                    <div class="flex justify-center">
                        <img src="{{ $project->icon_image_url }}" alt="Project Icon" class="w-48 h-48 rounded-lg object-cover">
                    </div>
                @endif
                
                <div>
                    <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $project->project_title }}</h4>
                    <p class="mt-2 text-base text-gray-600 dark:text-gray-400">
                        {{ $project->project_description }}
                    </p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end items-center pt-4 border-t border-gray-200 dark:border-gray-700 mt-4">
                <button type="button" 
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none"
                        wire:click="closeModal">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif
</div> 