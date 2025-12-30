<div>
    <button wire:click="open" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" title="Manage Photos">
        <i class="fas fa-images mr-1"></i> Photos
    </button>
    
    @if ($showModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center" wire:click.self="closeModal">
        <div class="relative p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Manage Photos for "{{ $project?->project_title ?? '' }}"
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" wire:click="closeModal">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mt-4">
                @if (session()->has('message'))
                    <div class="mb-4 p-2 bg-green-100 text-green-700 rounded text-sm">
                        {{ session('message') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="mb-4 p-2 bg-red-100 text-red-700 rounded text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Upload Form -->
                <form wire:submit.prevent="savePhotos" class="mb-6">
                    @csrf
                    <div class="mb-4">
                        <label for="photos{{ $project->id}}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Photos to Upload:</label>
                        <input type="file" wire:model="photos" id="photos{{ $project->id}}" multiple class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none">
                        @error('photos.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    @if ($photos && count($photos) > 0)
                        <div class="mb-4 space-y-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Add Details for Selected Photos:</label>
                            <div class="grid grid-cols-1 gap-4">
                                @foreach ($photos as $index => $photo)
                                    <div class="flex items-start space-x-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg" wire:key="preview-{{ $index }}-{{ $refreshKey ?? 0 }}">
                                        <div class="w-24 h-24 flex-shrink-0 rounded overflow-hidden border border-gray-300 dark:border-gray-600">
                                            <img src="{{ $photo->temporaryUrl() }}" class="object-cover w-full h-full" alt="Preview">
                                        </div>
                                        <div class="flex-1 space-y-2">
                                            <input 
                                                type="text"
                                                wire:model="titles.{{ $index }}" 
                                                placeholder="Photo Title (optional)..."
                                                class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                            >
                                            <textarea 
                                                wire:model="descriptions.{{ $index }}" 
                                                placeholder="Photo Description (optional)..."
                                                class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                                rows="2"
                                            ></textarea>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50" 
                            wire:loading.attr="disabled">
                                <span wire:loading wire:target="savePhotos" class="inline-block animate-spin mr-2"><i class="fas fa-spinner"></i></span>
                                Save Photos
                            </button>
                        </div>
                    @endif
                </form>

                <!-- Existing Photos Gallery -->
                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    Saved Photos ({{ $project?->photos?->count() ?? 0 }})
                </h4>
                
                @if ($project?->photos && $project->photos->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">No photos have been added yet. Upload photos above to get started.</p>
                @elseif ($project?->photos && $project->photos->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto p-2 bg-gray-50 dark:bg-gray-900 rounded-lg" wire:key="photos-grid-{{ $refreshKey ?? 0 }}">
                        @foreach ($project->photos as $photo)
                            <div wire:key="photo-{{ $photo->id }}-{{ $photo->updated_at?->timestamp ?? time() }}-{{ $refreshKey ?? 0 }}" class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                                <div class="relative group mb-2">
                                    <img src="{{ $photo->image_url }}" 
                                         alt="Project Photo" 
                                         class="w-full h-48 object-cover rounded-lg border border-gray-200 dark:border-gray-700"
                                         loading="lazy">
                                    <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-lg">
                                        <button wire:click="deletePhoto({{ $photo->id }})" wire:confirm="Are you sure you want to delete this photo?" class="text-white text-2xl hover:text-red-500 transition-colors">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                @if ($editingPhotoId === $photo->id)
                                    <div class="mt-2 space-y-2">
                                        <input 
                                            type="text"
                                            wire:model="editingTitle" 
                                            class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Photo Title"
                                        >
                                        <textarea 
                                            wire:model="editingDescription" 
                                            class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                            rows="2"
                                            placeholder="Photo Description"
                                        ></textarea>
                                        <div class="flex justify-end space-x-2 mt-2">
                                            <button wire:click="cancelEdit" class="px-2 py-1 text-xs bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</button>
                                            <button wire:click="updatePhotoDetails" class="px-2 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">Update</button>
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-2 flex items-start justify-between">
                                        <div class="flex-1">
                                            <h5 class="text-sm font-bold text-gray-900 dark:text-white">
                                                {{ $photo->title ?: 'Untitled Photo' }}
                                            </h5>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 italic mt-1">
                                                {{ $photo->description ?: 'No description added.' }}
                                            </p>
                                        </div>
                                        <button wire:click="editPhotoDetails({{ $photo->id }})" class="ml-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Edit Photo Details">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
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
