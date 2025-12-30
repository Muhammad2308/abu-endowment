<div>
    <!-- Add Project Button -->
    <div class="mb-4 flex justify-end">
        <button wire:click="$dispatch('open-add-project-modal')" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
            <i class="fas fa-plus mr-2"></i> Add Project
        </button>
    </div>
    <!-- Search and Filters -->
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="flex-1">
            <div class="relative">
                <input wire:model.live="search" type="text" 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" 
                       placeholder="Search projects...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </div>
        <div class="w-full md:w-48">
            <select wire:model.live="statusFilter" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <option value="">All Statuses</option>
                <option value="active">Active Only</option>
                <option value="closed">Closed Only</option>
            </select>
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
    <!-- Projects Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-10">
        @foreach($projects as $project)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden group flex flex-col">
                <!-- Card Image/Icon -->
                <div class="relative h-48 bg-gray-100 dark:bg-gray-700 overflow-hidden">
                    @if($project->icon_image)
                        <img src="{{ $project->icon_image_url ?? asset('storage/' . $project->icon_image) }}" 
                             alt="{{ $project->project_title }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="hidden absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-400">
                            <i class="fas fa-project-diagram text-4xl"></i>
                        </div>
                    @else
                        <div class="flex items-center justify-center h-full text-gray-400">
                            <i class="fas fa-project-diagram text-4xl"></i>
                        </div>
                    @endif
                    
                    <!-- Photos Count Badge -->
                    <div class="absolute top-3 right-3">
                        <span class="px-2.5 py-1 bg-black/60 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider rounded-lg flex items-center shadow-lg">
                            <i class="fas fa-images mr-1.5 text-blue-400"></i> {{ $project->photos_count }}
                        </span>
                    </div>

                    <!-- Status Badge -->
                    <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 {{ $project->status === 'active' ? 'bg-green-500/80' : 'bg-red-500/80' }} backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider rounded-lg flex items-center shadow-lg">
                            <i class="fas {{ $project->status === 'active' ? 'fa-check-circle' : 'fa-times-circle' }} mr-1.5"></i> {{ $project->status }}
                        </span>
                    </div>

                    <!-- Date Badge -->
                    <div class="absolute bottom-3 left-3">
                        <span class="px-2 py-1 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm text-gray-600 dark:text-gray-300 text-[10px] font-medium rounded-md shadow-sm">
                            {{ $project->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-5 flex-1 flex flex-col">
                    <div class="mb-3">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1 line-clamp-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ $project->project_title }}
                        </h3>
                        <div class="h-1 w-12 bg-blue-500 rounded-full"></div>
                    </div>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-4 flex-1">
                        {{ $project->project_description }}
                    </p>

                    <!-- Funding Progress -->
                    <div class="mb-6 space-y-2">
                        <div class="flex justify-between text-xs font-bold uppercase tracking-wider">
                            <span class="text-blue-600 dark:text-blue-400">Raised: ₦{{ number_format($project->raised, 2) }}</span>
                            <span class="text-gray-500 dark:text-gray-400">Target: ₦{{ number_format($project->target, 2) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                            <div class="bg-blue-500 h-full rounded-full transition-all duration-500" 
                                 style="width: {{ $project->target > 0 ? min(($project->raised / $project->target) * 100, 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex space-x-2">
                             @livewire('admin.view-project-details', ['project' => $project], key('card-view-' . $project->id))
                             @livewire('admin.add-project-photos', ['project' => $project], key('card-photos-' . $project->id))
                        </div>
                        <button wire:click="editProject({{ $project->id }})" 
                                class="w-8 h-8 flex items-center justify-center text-yellow-500 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 rounded-full transition-all duration-200"
                                title="Edit Project">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex items-center space-x-3 mb-6">
        <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Detailed View</span>
        <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
    </div>

    <!-- Projects Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                    <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Icon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Target</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Raised</th>
                    <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Photos</th>
                    <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($projects as $project)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $project->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $project->project_title }}</td>
                        <td class="hidden md:table-cell px-6 py-4 text-sm text-gray-900 dark:text-white">
                            <div class="max-w-xs truncate" title="{{ $project->project_description }}">
                                {{ $project->project_description }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($project->icon_image)
                                <img src="{{ $project->icon_image_url ?? asset('storage/' . $project->icon_image) }}" 
                                     alt="Project Icon" 
                                     class="w-12 h-12 rounded-lg object-cover border border-gray-200 dark:border-gray-700"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">
                                <span class="hidden inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    No Icon
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    No Icon
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ ucfirst($project->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            ₦{{ number_format($project->target, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600 dark:text-green-400">
                            ₦{{ number_format($project->raised, 2) }}
                        </td>
                        <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $project->photos_count }} photos
                            </span>
                        </td>
                        <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $project->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                @livewire('admin.add-project-photos', ['project' => $project], key('photos-' . $project->id))
                                @livewire('admin.view-project-details', ['project' => $project], key('view-' . $project->id))
                                <button wire:click="editProject({{ $project->id }})"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400"
                                        title="Edit Project">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                                <!-- <button wire:click="showDonations({{ $project->id }})"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                        title="View Donations">
                                    <i class="fas fa-donate mr-1"></i> Donations
                                </button> -->
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
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
</div>

@if($showDonationsModal && $selectedProject)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-data="{ show: @entangle('showDonationsModal') }" x-show="show" x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Donations for {{ $selectedProject->project_title }}</h3>
                <button @click="$wire.set('showDonationsModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl font-bold">&times;</button>
            </div>
            <div class="overflow-y-auto p-6 flex-1">
                <!-- Project Images Section -->
                @if($selectedProject->photos && $selectedProject->photos->count() > 0)
                    <div class="mb-6">
                        <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3">Project Images</h4>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($selectedProject->photos as $photo)
                                <div class="relative group">
                                    @php
                                        $imageUrl = $photo->image_url;
                                    @endphp
                                    <img src="{{ $imageUrl }}" 
                                         alt="Project Photo" 
                                         class="w-full h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:opacity-90 transition-opacity"
                                         onclick="window.open('{{ $imageUrl }}', '_blank')"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <div class="hidden w-full h-32 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">Image not found</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Donations List -->
                <div>
                    <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3">
                        Donations ({{ $selectedDonations->count() }})
                    </h4>
                    @forelse($selectedDonations as $donation)
                        <div class="mb-4 p-4 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                            <div class="font-semibold text-green-700 dark:text-green-400">Amount: ₦{{ number_format($donation->amount, 2) }}</div>
                            <div class="text-sm text-gray-700 dark:text-gray-300">Date: {{ $donation->created_at->format('M d, Y H:i') }}</div>
                            <div class="text-sm text-gray-700 dark:text-gray-300">Donor: {{ $donation->donor->surname ?? '' }} {{ $donation->donor->name ?? '' }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Reference: {{ $donation->payment_reference ?? '—' }}</div>
                        </div>
                    @empty
                        <div class="text-gray-500 dark:text-gray-400">No donations found for this project.</div>
                    @endforelse
                </div>
            </div>
            <div class="flex justify-end gap-2 px-6 py-4 border-t dark:border-gray-700">
                <button @click="$wire.set('showDonationsModal', false)" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600">Close</button>
            </div>
        </div>
    </div>
@endif

</div>
