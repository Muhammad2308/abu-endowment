<div>
    @if ($showModal && $project)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Manage Photos for "{{ $project->project_title }}"
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" wire:click="closeModal">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mt-4">
                <!-- Upload Form -->
                <form wire:submit.prevent="savePhotos" class="mb-6">
                    @if ($photos)
                        <div class="mb-4 flex flex-wrap gap-2">
                            @foreach ($photos as $photo)
                                <div class="w-24 h-24 rounded overflow-hidden border">
                                    <img src="{{ $photo->temporaryUrl() }}" class="object-cover w-full h-full" alt="Preview">
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="mb-4">
                        <label for="photos" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Photos to Upload:</label>
                        <input type="file" wire:model="photos" id="photos" multiple class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none">
                        @error('photos.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50" wire:loading.attr="disabled">
                            <span wire:loading wire:target="savePhotos" class="inline-block animate-spin mr-2"><i class="fas fa-spinner"></i></span>
                            Save Photos
                        </button>
                    </div>
                </form>

                <!-- Existing Photos Gallery -->
                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Existing Photos ({{ $project->photos->count() }})</h4>
                @if ($project->photos->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">No photos have been added yet.</p>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-h-72 overflow-y-auto p-2 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        @foreach ($project->photos as $photo)
                            <div wire:key="photo-{{ $photo->id }}" class="relative group">
                                <img src="{{ asset('storage/' . $photo->body_image) }}" alt="Project Photo" class="w-full h-40 object-cover rounded-lg">
                                <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="deletePhoto({{ $photo->id }})" wire:confirm="Are you sure you want to delete this photo?" class="text-white text-3xl hover:text-red-500 transition-colors">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end items-center pt-4 border-t border-gray-200 dark:border-gray-700 mt-4">
                <button type="button" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none" wire:click="closeModal">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    function openPhotoModal(photoId, imageUrl) {
        document.getElementById('photoModal' + photoId).style.display = 'block';
    }

    function closePhotoModal(photoId) {
        document.getElementById('photoModal' + photoId).style.display = 'none';
    }

    document.addEventListener('livewire:init', () => {
        Livewire.on('open-add-photos-modal', (event) => {
            document.getElementById('addProjectPhotosModal').style.display = 'block';
        });

        Livewire.on('view-project-photos', (event) => {
            document.getElementById('addProjectPhotosModal').style.display = 'block';
        });

        Livewire.on('closeModal', () => {
            document.getElementById('addProjectPhotosModal').style.display = 'none';
        });

        // Close modal when photos are saved
        Livewire.on('photos-added', () => {
            document.getElementById('addProjectPhotosModal').style.display = 'none';
        });

        // Close modal when photo is deleted
        Livewire.on('photo-deleted', () => {
            // Don't close modal, just refresh the view
        });
    });
</script> 