<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-slate-800 dark:text-white">Payment Transactions</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Track all gateway events for donations and payments.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <input type="text" wire:model.debounce.500ms="search" placeholder="Search transactions..." class="w-full sm:w-80 px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500" />
            <select wire:model="gateway" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">All gateways</option>
                <option value="paystack">Paystack</option>
                <option value="squad">Squad</option>
                <option value="manual">Manual</option>
            </select>
            <select wire:model="status" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">All status</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="success">Success</option>
                <option value="failed">Failed</option>
            </select>
            <select wire:model="category" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">All categories</option>
                <option value="general">General</option>
                <option value="project">Project</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-slate-50 dark:bg-slate-700">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Gateway</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Event</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Donor / Project</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Amount</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($transactions as $transaction)
                    <tr>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">{{ ucfirst($transaction->payment_gateway) }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">{{ ucfirst($transaction->category ?? 'N/A') }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">{{ ucfirst(str_replace(['.', '_'], ' ', $transaction->event_type)) }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                            <div>{{ optional($transaction->donor)->full_name ?? 'N/A' }}</div>
                            <div class="text-xs text-slate-400 dark:text-slate-500">{{ optional($transaction->project)->project_title ?? 'General' }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">{{ $transaction->currency }} {{ number_format($transaction->amount ?? 0, 2) }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ in_array($transaction->status, ['success', 'completed']) ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($transaction->status ?? 'unknown') }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="viewTransaction({{ $transaction->id }})" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-900 dark:text-emerald-400 px-3 py-1 rounded-lg transition">
                                <i class="fas fa-arrow-right text-xs"></i>
                                View
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400">No transactions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>

    <!-- Transaction Details Sidebar -->
    <div class="fixed inset-y-0 right-0 z-50 w-full max-w-md {{ $showDetailsModal ? 'translate-x-0' : 'translate-x-full' }} transform transition-transform duration-300 ease-in-out bg-white dark:bg-slate-900 shadow-2xl flex flex-col">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-slate-900 bg-opacity-50 {{ $showDetailsModal ? 'opacity-100' : 'opacity-0 pointer-events-none' }} transition-opacity duration-300 z-40" wire:click="closeModal"></div>

        @if($showDetailsModal && $selectedTransaction)
            <!-- Sidebar Header -->
            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-5 text-white flex items-start justify-between">
                <div>
                    <h3 class="text-xl font-bold">Transaction Details</h3>
                    <p class="text-sm text-emerald-100 mt-1">Transaction ID: {{ $selectedTransaction->id }}</p>
                </div>
                <button type="button" wire:click="closeModal" class="text-emerald-100 hover:text-white transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Sidebar Content -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                <!-- Status Badge -->
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ in_array($selectedTransaction->status, ['success', 'completed']) ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                        {{ ucfirst($selectedTransaction->status ?? 'unknown') }}
                    </span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                        {{ ucfirst($selectedTransaction->category ?? 'N/A') }}
                    </span>
                </div>

                <!-- Amount Section -->
                <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-700 rounded-lg p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400 font-semibold">Amount</p>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">{{ $selectedTransaction->currency }} {{ number_format($selectedTransaction->amount ?? 0, 2) }}</p>
                    @if($selectedTransaction->fee)
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">Fee: {{ $selectedTransaction->currency }} {{ number_format($selectedTransaction->fee, 2) }}</p>
                    @endif
                </div>

                <!-- Basic Information -->
                <div class="space-y-4">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400 font-semibold mb-2">Gateway</p>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-credit-card text-slate-400"></i>
                            <p class="text-sm text-slate-700 dark:text-slate-200">{{ ucfirst($selectedTransaction->payment_gateway) }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400 font-semibold mb-2">Event Type</p>
                        <p class="text-sm text-slate-700 dark:text-slate-200 bg-slate-50 dark:bg-slate-800 rounded px-3 py-2">{{ ucfirst(str_replace(['.', '_'], ' ', $selectedTransaction->event_type)) }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400 font-semibold mb-2">Channel</p>
                        <p class="text-sm text-slate-700 dark:text-slate-200">{{ $selectedTransaction->channel ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400 font-semibold mb-2">Date & Time</p>
                        <p class="text-sm text-slate-700 dark:text-slate-200">{{ $selectedTransaction->created_at->format('M d, Y · H:i:s') }}</p>
                    </div>
                </div>

                <!-- Donor Information -->
                @if($selectedTransaction->donor)
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400 font-semibold mb-3">Donor Information</p>
                        <div class="space-y-2">
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Name</p>
                                <p class="text-sm text-slate-700 dark:text-slate-200">{{ $selectedTransaction->donor->full_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Email</p>
                                <p class="text-sm text-slate-700 dark:text-slate-200">{{ $selectedTransaction->donor->email }}</p>
                            </div>
                            @if($selectedTransaction->donor->phone)
                                <div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Phone</p>
                                    <p class="text-sm text-slate-700 dark:text-slate-200">{{ $selectedTransaction->donor->phone }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Project Information -->
                @if($selectedTransaction->project)
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400 font-semibold mb-3">Project Information</p>
                        <div class="space-y-2">
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Project Title</p>
                                <p class="text-sm text-slate-700 dark:text-slate-200">{{ $selectedTransaction->project->project_title }}</p>
                            </div>
                            @if($selectedTransaction->project->description)
                                <div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Description</p>
                                    <p class="text-sm text-slate-700 dark:text-slate-200 line-clamp-3">{{ $selectedTransaction->project->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Payment References -->
                <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400 font-semibold mb-3">Payment References</p>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Payment Reference</p>
                            <code class="block text-xs text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800 rounded px-2 py-1 break-all">{{ $selectedTransaction->payment_reference ?? 'N/A' }}</code>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Gateway Reference</p>
                            <code class="block text-xs text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800 rounded px-2 py-1 break-all">{{ $selectedTransaction->gateway_reference ?? 'N/A' }}</code>
                        </div>
                    </div>
                </div>

                <!-- Status Message -->
                @if($selectedTransaction->message)
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400 font-semibold mb-2">Message</p>
                        <p class="text-sm text-slate-700 dark:text-slate-200 bg-slate-50 dark:bg-slate-800 rounded p-3">{{ $selectedTransaction->message }}</p>
                    </div>
                @endif

                <!-- Raw Payload -->
                <div class="border-t border-slate-200 dark:border-slate-700 pt-4 pb-6">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400 font-semibold mb-2">Raw Payload</p>
                    <div class="bg-slate-900 rounded-lg overflow-x-auto">
                        <pre class="text-xs text-slate-300 p-3">{{ json_encode(json_decode($selectedTransaction->response_payload ?? '{}'), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
                </div>
            </div>

            <!-- Sidebar Footer -->
            <div class="border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-6 py-4">
                <button wire:click="closeModal" class="w-full px-4 py-2 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg transition">
                    Close Sidebar
                </button>
            </div>
        @endif
    </div>
</div>
