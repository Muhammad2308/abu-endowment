<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}

    <!-- Modal toggle -->
    <button wire:click="openModal" type="button" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200 block w-full text-left">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-upload text-indigo-600 text-3xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Upload Alumni</h3>
                    <p class="text-sm text-gray-500">Import alumni data from Excel or CSV</p>
                </div>
            </div>
        </div>
    </button>

    @if($showModal)
    <!-- Main modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900 bg-opacity-50">
        <div class="relative p-4 w-full max-w-4xl h-full md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex justify-between items-start p-4 rounded-t border-b dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Upload Alumni Data
                    </h3>
                    <button wire:click="closeModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
                    </button>
                </div>
                <!-- Modal body -->
                <form wire:submit.prevent="import">
                    <div class="p-6 space-y-6">
                        @if($importing && !$importFinished)
                            <div class="w-full text-center p-4">
                                <i class="fas fa-spinner fa-spin text-4xl text-indigo-600"></i>
                                <p class="mt-2 text-lg font-medium text-gray-700 dark:text-gray-300">Importing... Please wait.</p>
                            </div>
                        @elseif($importFinished)
                            <div class="w-full text-center p-4">
                                @if($successMessage)
                                <i class="fas fa-check-circle text-4xl text-green-500"></i>
                                <p class="mt-2 text-lg font-medium text-green-700 dark:text-green-400">{{ $successMessage }}</p>
                                @endif
                                @if($errorMessage)
                                <i class="fas fa-exclamation-triangle text-4xl text-red-500"></i>
                                <p class="mt-2 text-lg font-medium text-red-700 dark:text-red-400">{{ $errorMessage }}</p>
                                @endif
                                
                                <!-- Import Statistics -->
                                <div class="mt-4 grid grid-cols-3 gap-4 text-sm">
                                    <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg">
                                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $importedCount }}</div>
                                        <div class="text-green-700 dark:text-green-300">Imported</div>
                                    </div>
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg">
                                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $skippedCount }}</div>
                                        <div class="text-yellow-700 dark:text-yellow-300">Skipped</div>
                                    </div>
                                    <div class="bg-red-50 dark:bg-red-900/20 p-3 rounded-lg">
                                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $failedCount }}</div>
                                        <div class="text-red-700 dark:text-red-300">Failed</div>
                                    </div>
                                </div>
                                
                                @if($failures && count($failures) > 0)
                                <div class="mt-4 text-left">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Error Details:</h4>
                                    <div class="max-h-60 overflow-y-auto border rounded-lg p-4 bg-gray-50 dark:bg-gray-800">
                                        @foreach($failures as $failure)
                                        <div class="mb-2 p-2 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 rounded">
                                            <p class="text-sm font-medium text-red-800 dark:text-red-200">Row {{ $failure['row'] }}:</p>
                                            <ul class="text-xs text-red-700 dark:text-red-300 ml-4">
                                                @foreach($failure['errors'] as $error)
                                                <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        @else
                            <div class="w-full">
                                <label for="upload" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Upload file</label>
                                <input wire:model="upload" type="file" id="upload" class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">Allowed file types: XLS, XLSX, CSV (Max. 10MB).</p>
                                @error('upload') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                <p class="font-bold mb-2">Please ensure your file has a header row with the following columns:</p>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                        <span class="font-mono text-xs">surname</span>
                                    </div>
                                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                        <span class="font-mono text-xs">name</span>
                                    </div>
                                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                        <span class="font-mono text-xs">other_name</span>
                                    </div>
                                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                        <span class="font-mono text-xs">gender (male|female)</span>
                                    </div>
                                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                        <span class="font-mono text-xs">reg_number</span>
                                    </div>
                                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                        <span class="font-mono text-xs">lga</span>
                                    </div>
                                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                        <span class="font-mono text-xs">nationality</span>
                                    </div>
                                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                        <span class="font-mono text-xs">state</span>
                                    </div>
                                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                        <span class="font-mono text-xs">address</span>
                                    </div>
                                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                        <span class="font-mono text-xs">email</span>
                                    </div>
                                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                        <span class="font-mono text-xs">phone</span>
                                    </div>
                                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                        <span class="font-mono text-xs">entry_year</span>
                                    </div>
                                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                        <span class="font-mono text-xs">graduation_year</span>
                                    </div>
                                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                        <span class="font-mono text-xs">faculty</span>
                                    </div>
                                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                        <span class="font-mono text-xs">department</span>
                                    </div>
                                </div>
                                <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 rounded">
                                    <p class="text-sm text-blue-800 dark:text-blue-200">
                                        <strong>Note:</strong> The system will automatically create faculties and departments if they don't exist. 
                                        Visions will be managed separately through the admin interface.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                        @if(!$importing)
                        <button type="submit" class="text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800" @if(!$upload) disabled @endif>
                            Import Data
                        </button>
                        @endif
                        <button wire:click="closeModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                            {{ ($importFinished) ? 'Close' : 'Cancel' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
