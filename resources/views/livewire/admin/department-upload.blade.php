<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <i class="fas fa-building text-3xl text-indigo-500"></i>
        </div>
        <div class="ml-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Upload Departments</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Bulk upload departments from an Excel file.</p>
            <button wire:click="openModal" class="mt-2 px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700">
                Upload File
            </button>
        </div>
    </div>

    @if($showModal)
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Upload Departments File
                            </h3>
                            <div class="mt-2">
                                @if ($importing && !$importFinished)
                                    <div class="py-4">
                                        <p class="text-sm text-gray-500">Importing... please wait.</p>
                                        <!-- You can add a spinner here -->
                                    </div>
                                @elseif ($importFinished)
                                    @if ($successMessage)
                                        <p class="text-sm text-green-600">{{ $successMessage }}</p>
                                    @endif
                                    @if ($errorMessage)
                                        <div class="text-sm text-red-600">
                                            {!! $errorMessage !!}
                                        </div>
                                    @endif
                                @else
                                    <form wire:submit.prevent="import">
                                        <div class="mt-4">
                                            <input type="file" wire:model="upload">
                                            @error('upload') <span class="error text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    @if (!$importFinished)
                    <button wire:click.prevent="import" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm" wire:loading.attr="disabled">
                        Import
                    </button>
                    @endif
                    <button wire:click="$set('showModal', false)" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
