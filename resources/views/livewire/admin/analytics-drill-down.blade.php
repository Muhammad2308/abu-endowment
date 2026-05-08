@section('title', 'Analytics Details')

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.statistics') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-arrow-left"></i> Back to Analytics
                </a>
            </div>
            <h1 class="mt-2 text-2xl font-bold text-slate-800 dark:text-white tracking-tight">{{ $title }}</h1>
        </div>
        <div>
            <button wire:click="export" wire:loading.attr="disabled" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed mr-3">
                <i class="fas fa-file-excel mr-2"></i>
                <span wire:loading.remove wire:target="export">Export to Excel</span>
                <span wire:loading wire:target="export">Exporting...</span>
            </button>
            <div class="inline-flex items-center px-4 py-2 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-lg text-sm font-medium">
                {{ $donations->total() }} Records Found
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-700/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Donor</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Amount</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Type / Project</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Details</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($donations as $donation)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/25 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-400">
                                    <i class="fas fa-user text-xs"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $donation->donor->name ?? 'Guest' }} {{ $donation->donor->surname ?? '' }}
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $donation->donor->email ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-900 dark:text-white">
                                ₦{{ number_format($donation->amount, 2) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-900 dark:text-white">
                                {{ ucfirst($donation->type) }}
                            </div>
                            @if($donation->project)
                            <div class="text-xs text-emerald-600 dark:text-emerald-400">
                                {{ Str::limit($donation->project->project_title, 20) }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1">
                                @if($donation->donor && $donation->donor->faculty)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    {{ $donation->donor->faculty->name }}
                                </span>
                                @endif
                                @if($donation->donor && $donation->donor->department)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                                    {{ $donation->donor->department->name }}
                                </span>
                                @endif
                                @if($donation->donor && $donation->donor->state)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300">
                                    {{ $donation->donor->state }}
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                {{ $donation->created_at->format('M d, Y') }}
                            </div>
                            <div class="text-xs text-slate-400">
                                {{ $donation->created_at->format('h:i A') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                {{ ucfirst($donation->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                            <i class="fas fa-search mb-2 text-2xl text-slate-300"></i>
                            <p>No records found for this selection.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($donations->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
            {{ $donations->links() }}
        </div>
        @endif
    </div>
</div>
