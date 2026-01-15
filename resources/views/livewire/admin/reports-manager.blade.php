<div>
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Generate Report</h3>
        <button wire:click="export" wire:loading.attr="disabled" class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 disabled:opacity-50">
            <span wire:loading.remove wire:target="export"><i class="fas fa-file-export mr-2"></i> Export</span>
            <span wire:loading wire:target="export"><i class="fas fa-spinner fa-spin mr-2"></i> Exporting...</span>
        </button>
    </div>
    
    <div class="p-6">
        <!-- Filter Section -->
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Filters</h4>
                <button wire:click="resetFilters" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                    Reset Filters
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Faculty -->
                <div>
                    <label for="faculty" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Faculty</label>
                    <select wire:model.live="selectedFaculty" id="faculty" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white sm:text-sm">
                        <option value="">All Faculties</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->current_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Department -->
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department</label>
                    <select wire:model.live="selectedDepartment" id="department" 
                            @if(empty($selectedFaculty)) disabled @endif
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white sm:text-sm disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-gray-900">
                        <option value="">{{ empty($selectedFaculty) ? 'Select Faculty First' : 'All Departments' }}</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->current_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Programme (Placeholder) -->
                <div>
                    <label for="programme" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Programme</label>
                    <select disabled class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-gray-100 dark:bg-gray-900 text-gray-500 sm:text-sm">
                        <option value="">Not Available</option>
                    </select>
                </div>

                <!-- Project -->
                <div>
                    <label for="project" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Project</label>
                    <select wire:model.live="selectedProject" id="project" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white sm:text-sm">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->project_title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label for="dateFrom" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date From</label>
                    <input wire:model.live="dateFrom" type="date" id="dateFrom" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white sm:text-sm">
                </div>

                <!-- Date To -->
                <div>
                    <label for="dateTo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date To</label>
                    <input wire:model.live="dateTo" type="date" id="dateTo" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white sm:text-sm">
                </div>

                <!-- Min Amount -->
                <div>
                    <label for="minAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Min Amount</label>
                    <input wire:model.live.debounce.500ms="minAmount" type="number" id="minAmount" placeholder="0.00" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white sm:text-sm">
                </div>

                 <!-- Max Amount -->
                 <div>
                    <label for="maxAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Amount</label>
                    <input wire:model.live.debounce.500ms="maxAmount" type="number" id="maxAmount" placeholder="Max" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white sm:text-sm">
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select wire:model.live="selectedStatus" id="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white sm:text-sm">
                        <option value="">All Statuses</option>
                        <option value="paid">Paid</option>
                        <option value="success">Success</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>

                <!-- Donor Phone -->
                <div>
                    <label for="donor_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Donor Phone</label>
                    <input wire:model.live.debounce.500ms="donorPhone" type="text" id="donor_phone" placeholder="Phone Number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white sm:text-sm">
                </div>

                <!-- Donor Name -->
                <div>
                    <label for="donor_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Donor Name</label>
                    <input wire:model.live.debounce.500ms="donorName" type="text" id="donor_name" placeholder="Name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white sm:text-sm">
                </div>
            </div>
            
            <div wire:loading class="mt-2 text-sm text-blue-600">
                <i class="fas fa-spinner fa-spin mr-2"></i> Loading results...
            </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Donor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Faculty/Dept</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($donations as $donation)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $donation->created_at->format('Y-m-d') }}<br>
                            <span class="text-xs text-gray-500">{{ $donation->created_at->format('H:i') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $donation->donor->full_name ?? 'Unknown' }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $donation->donor->phone ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $donation->project->project_title ?? 'General Donation' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $donation->donor->faculty->current_name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $donation->donor->department->current_name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600 dark:text-green-400">
                            ₦{{ number_format($donation->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($donation->status === 'paid' || $donation->status === 'success')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-search mb-2 text-2xl block"></i>
                            No records found matching the filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $donations->links() }}
        </div>
    </div>
</div>
