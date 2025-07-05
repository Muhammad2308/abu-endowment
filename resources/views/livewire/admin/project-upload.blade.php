<div>
    @if ($showModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center" id="project-upload-modal">
        <div class="relative p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Upload Projects from Excel</h3>
                <div class="mt-2 px-7 py-3">
                    <form wire:submit.prevent="uploadProjects">
                        @if (session()->has('message'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span class="block sm:inline">{{ session('message') }}</span>
                            </div>
                        @endif
                        @if ($errorMessage)
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span class="block sm:inline">{{ $errorMessage }}</span>
                            </div>
                        @endif

                        <!-- Instructions -->
                        <div class="bg-blue-50 dark:bg-gray-700 border border-blue-200 dark:border-blue-600 rounded-md p-4 mb-4 text-left">
                            <div class="flex">
                                <i class="fas fa-info-circle text-blue-400 mt-0.5 mr-3"></i>
                                <div>
                                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">Instructions:</h4>
                                    <ul class="mt-1 text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                        <li>• Upload an Excel file (.xlsx or .xls)</li>
                                        <li>• Required columns: <strong>project_title</strong>, <strong>project_description</strong></li>
                                        <li>• Maximum file size: 10MB</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="excelFile" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 text-left">Select Excel File:</label>
                            <input type="file" wire:model="excel_file" id="excelFile" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-600 leading-tight focus:outline-none focus:shadow-outline" accept=".xlsx,.xls">
                            @error('excel_file') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                        </div>

                        @if($excel_file)
                            <div class="bg-green-50 dark:bg-gray-700 border border-green-200 dark:border-green-600 rounded-md p-3 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-400 mt-0.5 mr-2"></i>
                                    <span class="text-sm text-green-700 dark:text-green-300">File selected: {{ $excel_file->getClientOriginalName() }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="items-center px-4 py-3">
                            <button id="upload-projects-btn" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300" {{ !$excel_file ? 'disabled' : '' }}>
                                <span wire:loading wire:target="uploadProjects" class="inline-block animate-spin mr-2">
                                    <i class="fas fa-spinner"></i>
                                </span>
                                Upload Projects
                            </button>
                        </div>
                    </form>
                </div>
                <div class="items-center px-4 py-3">
                    <button wire:click="closeModal" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div> 