<div>
    <!-- Search and Filters -->
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="flex-1">
            <div class="relative">
                <input wire:model.live="search" type="text" 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" 
                       placeholder="Search projects by name or description...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </div>
        <div class="w-full md:w-48">
            <select wire:model.live="perPage" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>
    </div>

    <!-- Projects Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Project Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Target</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Raised</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Donors</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($projects as $project)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $project->id ?? '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $project->project_title ?? 'Endowment Project' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            @if($project->target)
                                <div>
                                    <div>₦{{ number_format($project->target, 2) }}</div>
                                    @php
                                        $amountInWords = \App\Helpers\NumberToWords::convert((int)$project->target);
                                    @endphp
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $amountInWords }} Naira Only
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            @php
                                // Use raised_amount from query or calculate from donations
                                $raised = $project->raised_amount ?? ($project->raised ?? 0);
                            @endphp
                            ₦{{ number_format($raised, 2) }}
                            @if($project->target && $project->target > 0)
                                @php
                                    $percentage = ($raised / $project->target) * 100;
                                @endphp
                                <div class="mt-1">
                                    <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($percentage, 1) }}%</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @php
                                $donorCount = $project->donor_count ?? 0;
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                <i class="fas fa-users mr-1"></i>
                                {{ $donorCount }} {{ $donorCount === 1 ? 'Donor' : 'Donors' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button wire:click="showProjectDetails({{ $project->id ?? 'null' }})"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    title="View Project Details">
                                <i class="fas fa-eye mr-1"></i> Details
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No projects found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between mt-6">
        <div class="text-sm text-gray-700 dark:text-gray-300">
            Showing {{ $projects->firstItem() ?? 0 }} to {{ $projects->lastItem() ?? 0 }} of {{ $projects->total() }} projects
        </div>
        <div>
            {{ $projects->links() }}
        </div>
    </div>

    <!-- Project Details Modal -->
    @if($showDetailsModal && $selectedProject)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" 
             x-data="{ show: @entangle('showDetailsModal') }" 
             x-show="show" 
             x-cloak
             @keydown.escape.window="$wire.closeDetailsModal()">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Project Details: {{ $selectedProject->project_title ?? 'Endowment Project' }}
                    </h3>
                    <button @click="$wire.closeDetailsModal()" 
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl font-bold">&times;</button>
                </div>
                <div class="overflow-y-auto p-6 flex-1">
                    <!-- Project Information -->
                    <div class="mb-6">
                        <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3">Project Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Project Name</label>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $selectedProject->project_title ?? 'Endowment Project' }}</p>
                            </div>
                            @if($selectedProject->project_description ?? null)
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $selectedProject->project_description }}</p>
                            </div>
                            @endif
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Target</label>
                                <p class="text-sm text-gray-900 dark:text-white">
                                    @if($selectedProject->target ?? null)
                                        ₦{{ number_format($selectedProject->target, 2) }}
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">Not set</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Raised</label>
                                <p class="text-sm font-semibold text-green-600 dark:text-green-400">
                                    ₦{{ number_format($totalRaised, 2) }}
                                </p>
                            </div>
                            @if(($selectedProject->target ?? null) && ($selectedProject->target ?? 0) > 0)
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Progress</label>
                                @php
                                    $percentage = ($totalRaised / $selectedProject->target) * 100;
                                @endphp
                                <div class="mt-1">
                                    <div class="w-full bg-gray-200 rounded-full h-3 dark:bg-gray-700">
                                        <div class="bg-blue-600 h-3 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ number_format($percentage, 1) }}% complete</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Donations List -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-white">
                                Donations ({{ $selectedDonations->total() }})
                            </h4>
                            <div class="flex items-center gap-2">
                                <label class="text-sm text-gray-600 dark:text-gray-400">Show:</label>
                                <select wire:model.live="perPageModal" 
                                        class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <span class="text-sm text-gray-600 dark:text-gray-400">per page</span>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Donor</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Department/Faculty/Year</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Reference</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($selectedDonations as $donation)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                                @if($donation->donor)
                                                    <div>
                                                        <div class="font-medium">{{ $donation->donor->surname }} {{ $donation->donor->name }}</div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $donation->donor->email ?? $donation->donor->phone }}</div>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-500">Anonymous</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                                @if($donation->donor)
                                                    <div class="space-y-1">
                                                        @if($donation->donor->department)
                                                            <div class="text-xs">
                                                                <span class="font-medium text-gray-600 dark:text-gray-400">Dept:</span>
                                                                <span class="text-gray-900 dark:text-white">{{ $donation->donor->department->current_name ?? $donation->donor->department->name ?? '—' }}</span>
                                                            </div>
                                                        @endif
                                                        @if($donation->donor->faculty)
                                                            <div class="text-xs">
                                                                <span class="font-medium text-gray-600 dark:text-gray-400">Faculty:</span>
                                                                <span class="text-gray-900 dark:text-white">{{ $donation->donor->faculty->current_name ?? $donation->donor->faculty->name ?? '—' }}</span>
                                                            </div>
                                                        @endif
                                                        @if($donation->donor->entry_year)
                                                            <div class="text-xs">
                                                                <span class="font-medium text-gray-600 dark:text-gray-400">Entry:</span>
                                                                <span class="text-gray-900 dark:text-white">{{ $donation->donor->entry_year }}</span>
                                                            </div>
                                                        @endif
                                                        @if(!$donation->donor->department && !$donation->donor->faculty && !$donation->donor->entry_year)
                                                            <span class="text-gray-400 dark:text-gray-500 text-xs">—</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-500 text-xs">—</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm font-semibold text-green-600 dark:text-green-400">
                                                ₦{{ number_format($donation->amount, 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                                {{ $donation->created_at->format('M d, Y') }}
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $donation->created_at->format('H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                @if($donation->status === 'success' || $donation->status === 'paid')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        <i class="fas fa-check-circle mr-1"></i> Paid
                                                    </span>
                                                @elseif($donation->status === 'pending')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        <i class="fas fa-clock mr-1"></i> Pending
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                        {{ ucfirst($donation->status ?? 'Unknown') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $donation->payment_reference ?? '—' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                                No donations found for this project.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination Controls -->
                        @if($selectedDonations->hasPages())
                            <div class="flex items-center justify-between mt-4 px-4">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    Showing {{ $selectedDonations->firstItem() ?? 0 }} to {{ $selectedDonations->lastItem() ?? 0 }} of {{ $selectedDonations->total() }} donations
                                </div>
                                <div class="flex gap-1">
                                    {{-- Previous Button --}}
                                    <button wire:click="gotoModalPage({{ $selectedDonations->currentPage() - 1 }})"
                                            @if($selectedDonations->onFirstPage()) disabled @endif
                                            class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    
                                    {{-- Page Numbers --}}
                                    @foreach(range(1, $selectedDonations->lastPage()) as $page)
                                        @if($page == 1 || $page == $selectedDonations->lastPage() || abs($page - $selectedDonations->currentPage()) < 2)
                                            <button wire:click="gotoModalPage({{ $page }})"
                                                    class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md {{ $page == $selectedDonations->currentPage() ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600' }}">
                                                {{ $page }}
                                            </button>
                                        @elseif(abs($page - $selectedDonations->currentPage()) == 2)
                                            <span class="px-2 py-1 text-gray-500">...</span>
                                        @endif
                                    @endforeach
                                    
                                    {{-- Next Button --}}
                                    <button wire:click="gotoModalPage({{ $selectedDonations->currentPage() + 1 }})"
                                            @if(!$selectedDonations->hasMorePages()) disabled @endif
                                            class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex justify-end gap-2 px-6 py-4 border-t dark:border-gray-700">
                    <button @click="$wire.closeDetailsModal()" 
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
