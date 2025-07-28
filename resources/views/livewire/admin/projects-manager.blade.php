<div>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Icon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Photos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($projects as $project)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $project->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $project->project_title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            <div class="max-w-xs truncate" title="{{ $project->project_description }}">
                                {{ $project->project_description }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($project->icon_image)
                                <img src="{{ $project->icon_image_url }}" alt="Project Icon" 
                                     class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    No Icon
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $project->photos_count }} photos
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $project->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button wire:click="$dispatch('open-add-photos-modal', { projectId: {{ $project->id }} })" 
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        title="Manage Photos">
                                    <i class="fas fa-images mr-1"></i> Photos
                                </button>
                                <button wire:click="$dispatch('open-view-project-modal', { projectId: {{ $project->id }} })" 
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        title="View Details">
                                    <i class="fas fa-eye mr-1"></i> View
                                </button>
                                <button wire:click="showDonations({{ $project->id }})"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                        title="View Donations">
                                    <i class="fas fa-donate mr-1"></i> Donations
                                </button>
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
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl max-h-[80vh] flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-lg font-semibold">Donations for {{ $selectedProject->project_title }}</h3>
                <button @click="$wire.set('showDonationsModal', false)" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
            </div>
            <div class="overflow-y-auto p-6 flex-1">
                @forelse($selectedDonations as $donation)
                    <div class="mb-4 p-4 border rounded-lg bg-gray-50">
                        <div class="font-semibold text-green-700">Amount: ₦{{ number_format($donation->amount, 2) }}</div>
                        <div class="text-sm text-gray-700">Date: {{ $donation->created_at->format('M d, Y H:i') }}</div>
                        <div class="text-sm text-gray-700">Donor: {{ $donation->donor->surname ?? '' }} {{ $donation->donor->name ?? '' }}</div>
                        <div class="text-sm text-gray-500">Reference: {{ $donation->payment_reference }}</div>
                    </div>
                @empty
                    <div class="text-gray-500">No donations found for this project.</div>
                @endforelse
            </div>
            <div class="flex justify-end gap-2 px-6 py-4 border-t">
                <button @click="$wire.set('showDonationsModal', false)" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Close</button>
            </div>
        </div>
    </div>
@endif
