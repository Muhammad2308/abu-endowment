<div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    {{-- Summary Stats --}}
    <div wire:ignore class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide font-semibold">Total Collected</p>
            <p class="text-2xl font-bold text-slate-800 dark:text-white mt-1">₦{{ $chartData['totals']['all'] }}</p>
            <p class="text-xs text-slate-400 mt-1">last 30 days</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide font-semibold">Paystack</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">₦{{ $chartData['totals']['paystack'] }}</p>
            <p class="text-xs text-slate-400 mt-1">last 30 days</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide font-semibold">Squad</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">₦{{ $chartData['totals']['squad'] }}</p>
            <p class="text-xs text-slate-400 mt-1">last 30 days</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide font-semibold">Successful Txns</p>
            <p class="text-2xl font-bold text-slate-800 dark:text-white mt-1">{{ $chartData['totals']['count'] }}</p>
            <p class="text-xs text-slate-400 mt-1">all time</p>
        </div>
    </div>

    {{-- Line Chart --}}
    <div wire:ignore class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Transaction Trends</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">Daily payment volume — last 30 days</p>
            </div>
            <div class="flex items-center gap-4 text-xs text-slate-500">
                <span class="flex items-center gap-1"><span class="inline-block w-3 h-0.5 bg-emerald-500 rounded"></span> Paystack</span>
                <span class="flex items-center gap-1"><span class="inline-block w-3 h-0.5 bg-blue-500 rounded"></span> Squad</span>
            </div>
        </div>
        <div class="relative" style="height: 260px;">
            <canvas id="txnChart"></canvas>
        </div>
    </div>

    {{-- Filters + Table --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 {{ $showDetailsModal ? 'mr-[26rem]' : '' }}" style="transition: margin 0.3s ease;">
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
                        @if(!$showDetailsModal)<th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Category</th>@endif
                        @if(!$showDetailsModal)<th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Event</th>@endif
                        @if(!$showDetailsModal)<th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Donor / Project</th>@endif
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                @if($transaction->payment_gateway === 'paystack')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paystack
                                    </span>
                                @elseif($transaction->payment_gateway === 'squad')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Squad
                                    </span>
                                @else
                                    <span class="text-slate-600 dark:text-slate-300 text-xs">{{ ucfirst($transaction->payment_gateway) }}</span>
                                @endif
                            </td>
                            @if(!$showDetailsModal)<td class="px-4 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">{{ ucfirst($transaction->category ?? 'N/A') }}</td>@endif
                            @if(!$showDetailsModal)<td class="px-4 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">{{ ucfirst(str_replace(['.', '_'], ' ', $transaction->event_type)) }}</td>@endif
                            @if(!$showDetailsModal)
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                <div>{{ optional($transaction->donor)->full_name ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-400 dark:text-slate-500">{{ optional($transaction->project)->project_title ?? 'General' }}</div>
                            </td>
                            @endif
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-slate-700 dark:text-slate-200">₦{{ number_format($transaction->amount ?? 0, 2) }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                @php $s = $transaction->status; @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ in_array($s, ['success', 'completed']) ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' : '' }}
                                    {{ $s === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                    {{ in_array($s, ['failed', 'declined']) ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                ">
                                    {{ ucfirst($s ?? 'unknown') }}
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
                            <td colspan="{{ $showDetailsModal ? 5 : 8 }}" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>

    {{-- Transaction Details Sidebar --}}
    <div class="fixed top-0 right-0 h-screen z-50 w-full max-w-[24rem] {{ $showDetailsModal ? 'translate-x-0' : 'translate-x-full' }} transform transition-transform duration-300 ease-in-out bg-white dark:bg-slate-900 border-l border-slate-200 dark:border-slate-700 shadow-2xl flex flex-col overflow-hidden">

        @if($showDetailsModal && $selectedTransaction)
            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-5 text-white flex items-start justify-between">
                <div>
                    <h3 class="text-xl font-bold">Transaction Details</h3>
                    <p class="text-sm text-emerald-100 mt-1">Transaction ID: {{ $selectedTransaction->id }}</p>
                </div>
                <button type="button" wire:click="closeModal" class="text-emerald-100 hover:text-white transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ in_array($selectedTransaction->status, ['success', 'completed']) ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                        {{ ucfirst($selectedTransaction->status ?? 'unknown') }}
                    </span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                        {{ ucfirst($selectedTransaction->category ?? 'N/A') }}
                    </span>
                </div>

                <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-700 rounded-lg p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400 font-semibold">Amount</p>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">₦{{ number_format($selectedTransaction->amount ?? 0, 2) }}</p>
                    @if($selectedTransaction->fee)
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">Fee: ₦{{ number_format($selectedTransaction->fee, 2) }}</p>
                    @endif
                </div>

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

                @if($selectedTransaction->message)
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400 font-semibold mb-2">Message</p>
                        <p class="text-sm text-slate-700 dark:text-slate-200 bg-slate-50 dark:bg-slate-800 rounded p-3">{{ $selectedTransaction->message }}</p>
                    </div>
                @endif

                <div class="border-t border-slate-200 dark:border-slate-700 pt-4 pb-6">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400 font-semibold mb-2">Raw Payload</p>
                    <div class="bg-slate-900 rounded-lg overflow-x-auto">
                        <pre class="text-xs text-slate-300 p-3">{{ json_encode(json_decode($selectedTransaction->response_payload ?? '{}'), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-6 py-4">
                <button wire:click="closeModal" class="w-full px-4 py-2 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg transition">
                    Close Sidebar
                </button>
            </div>
        @endif
    </div>

    {{-- Chart initialisation --}}
    <script>
    (function () {
        var data = @json($chartData);

        function initChart() {
            var ctx = document.getElementById('txnChart');
            if (!ctx) return;
            if (ctx._chart) { ctx._chart.destroy(); }

            ctx._chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Paystack (₦)',
                            data: data.paystack,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16,185,129,0.08)',
                            borderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            tension: 0.4,
                            fill: true,
                        },
                        {
                            label: 'Squad (₦)',
                            data: data.squad,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59,130,246,0.08)',
                            borderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            tension: 0.4,
                            fill: true,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (ctx) {
                                    return ' ' + ctx.dataset.label + ': ₦' + ctx.parsed.y.toLocaleString('en-NG', { minimumFractionDigits: 2 });
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { maxRotation: 0, maxTicksLimit: 10, font: { size: 11 } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(148,163,184,0.15)' },
                            ticks: {
                                font: { size: 11 },
                                callback: function (v) {
                                    if (v >= 1000000) return '₦' + (v / 1000000).toFixed(1) + 'M';
                                    if (v >= 1000) return '₦' + (v / 1000).toFixed(0) + 'k';
                                    return '₦' + v;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Run after DOM is ready; also re-init after Livewire re-renders
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initChart);
        } else {
            initChart();
        }

        document.addEventListener('livewire:navigated', initChart);
        document.addEventListener('livewire:update', function () { setTimeout(initChart, 50); });
    })();
    </script>
</div>
