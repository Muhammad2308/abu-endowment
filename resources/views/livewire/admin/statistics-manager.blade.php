@section('title', 'View Analytics')

<div class="space-y-8 print:space-y-4" id="statistics-container">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 print:hidden">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white tracking-tight">Donation Analytics</h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400">Real-time insights into donation patterns and donor engagement.</p>
        </div>
        <div class="flex gap-3">
            <button id="printBtn" onclick="window.print()" class="group inline-flex items-center px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200">
                <i class="fas fa-print mr-2 text-slate-500 group-hover:text-slate-700 dark:text-slate-400 dark:group-hover:text-slate-200 transition-colors"></i>
                Print Report
            </button>
            <button wire:click="$refresh" class="group inline-flex items-center px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200">
                <i class="fas fa-sync-alt mr-2 text-emerald-500 group-hover:rotate-180 transition-transform duration-500" wire:loading.class="animate-spin"></i>
                Refresh Data
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 print:grid-cols-4 print:gap-4">
        <!-- Total Donations -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 dark:border-slate-700 hover:shadow-lg transition-shadow duration-300 print:shadow-none print:border print:border-slate-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Donations</p>
                    <h3 class="mt-2 text-2xl font-bold text-slate-800 dark:text-white">₦{{ number_format($totalDonations, 2) }}</h3>
                    <div class="mt-2 flex items-center text-xs">
                        <span class="{{ $donationGrowth >= 0 ? 'text-emerald-500' : 'text-rose-500' }} flex items-center font-medium">
                            <i class="fas fa-arrow-{{ $donationGrowth >= 0 ? 'up' : 'down' }} mr-1"></i> {{ number_format(abs($donationGrowth), 1) }}%
                        </span>
                        <span class="text-slate-400 ml-2">vs last month</span>
                    </div>
                </div>
                <div class="p-3 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl print:bg-transparent">
                    <i class="fas fa-hand-holding-dollar text-xl text-emerald-600 dark:text-emerald-400"></i>
                </div>
            </div>
        </div>

        <!-- Total Donors -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 dark:border-slate-700 hover:shadow-lg transition-shadow duration-300 print:shadow-none print:border print:border-slate-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Donors</p>
                    <h3 class="mt-2 text-2xl font-bold text-slate-800 dark:text-white">{{ number_format($totalDonors) }}</h3>
                    <div class="mt-2 flex items-center text-xs">
                        <span class="text-emerald-500 flex items-center font-medium">
                            <i class="fas fa-arrow-up mr-1"></i> {{ $newDonorsThisWeek }}
                        </span>
                        <span class="text-slate-400 ml-2">new this week</span>
                    </div>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-xl print:bg-transparent">
                    <i class="fas fa-users text-xl text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>

        <!-- Alumni Donors -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 dark:border-slate-700 hover:shadow-lg transition-shadow duration-300 print:shadow-none print:border print:border-slate-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Alumni Ratio</p>
                    <h3 class="mt-2 text-2xl font-bold text-slate-800 dark:text-white">{{ $alumniPercentage }}%</h3>
                    <div class="mt-2 flex items-center text-xs">
                        <span class="text-slate-400">of total donor base</span>
                    </div>
                </div>
                <div class="p-3 bg-amber-50 dark:bg-amber-900/30 rounded-xl print:bg-transparent">
                    <i class="fas fa-user-graduate text-xl text-amber-600 dark:text-amber-400"></i>
                </div>
            </div>
        </div>

        <!-- Avg. Donation -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 dark:border-slate-700 hover:shadow-lg transition-shadow duration-300 print:shadow-none print:border print:border-slate-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Avg. Donation</p>
                    <h3 class="mt-2 text-2xl font-bold text-slate-800 dark:text-white">₦{{ number_format($averageDonation, 2) }}</h3>
                    <div class="mt-2 flex items-center text-xs">
                        <!-- Placeholder for avg growth calculation if available -->
                        <span class="text-slate-400 ml-2">Overall average</span>
                    </div>
                </div>
                <div class="p-3 bg-purple-50 dark:bg-purple-900/30 rounded-xl print:bg-transparent">
                    <i class="fas fa-chart-line text-xl text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 print:grid-cols-2 print:gap-6 print:block" wire:ignore>
        <!-- Donor Name Chart -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden print:shadow-none print:border print:border-slate-200 print:mb-6 print:break-inside-avoid">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Top Donors</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Highest contributing individuals</p>
                </div>
            </div>
            <div class="p-6">
                <div class="relative h-80">
                    <canvas id="donorNameChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Faculty Chart -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden print:shadow-none print:border print:border-slate-200 print:mb-6 print:break-inside-avoid">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Top Faculties</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">By Amount Donated</p>
                </div>
            </div>
            <div class="p-6">
                <div class="relative h-80">
                    <canvas id="facultyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Department Chart -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden print:shadow-none print:border print:border-slate-200 print:mb-6 print:break-inside-avoid">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Top Departments (Distribution)</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">By Amount Donated</p>
                </div>
            </div>
            <div class="p-6">
                <div class="relative h-80">
                    <canvas id="departmentChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Department Chart (Bar) -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden print:shadow-none print:border print:border-slate-200 print:mb-6 print:break-inside-avoid">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Top Departments (Comparison)</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">By Amount Donated</p>
                </div>
            </div>
            <div class="p-6">
                <div class="relative h-80">
                    <canvas id="departmentBarChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Project Chart -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden print:shadow-none print:border print:border-slate-200 print:mb-6 print:break-inside-avoid">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Top Projects (Distribution)</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">By Amount Donated</p>
                </div>
            </div>
            <div class="p-6">
                <div class="relative h-80">
                    <canvas id="projectChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Project Chart (Bar) -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden print:shadow-none print:border print:border-slate-200 print:mb-6 print:break-inside-avoid">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Top Projects (Comparison)</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">By Amount Donated</p>
                </div>
            </div>
            <div class="p-6">
                <div class="relative h-80">
                    <canvas id="projectBarChart"></canvas>
                </div>
            </div>
        </div>

        <!-- State Chart -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden print:shadow-none print:border print:border-slate-200 print:mb-6 print:break-inside-avoid">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Top States</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">By Amount Donated</p>
                </div>
            </div>
            <div class="p-6">
                <div class="relative h-80">
                    <canvas id="stateChart"></canvas>
                </div>
            </div>
        </div>

        <!-- LGA Chart -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden print:shadow-none print:border print:border-slate-200 print:mb-6 print:break-inside-avoid">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Local Impact</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Top LGAs</p>
                </div>
            </div>
            <div class="p-6">
                <div class="relative h-80">
                    <canvas id="lgaChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Gender Chart -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden print:shadow-none print:border print:border-slate-200 print:mb-6 print:break-inside-avoid">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Demographics</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Gender Distribution</p>
                </div>
            </div>
            <div class="p-6">
                <div class="relative h-80">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
             // Theme Colors
            const isDarkMode = document.documentElement.classList.contains('dark');
            const theme = {
                colors: {
                    primary: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ec4899', '#ef4444'], 
                    grid: isDarkMode ? '#334155' : '#f1f5f9',
                    text: isDarkMode ? '#94a3b8' : '#64748b',
                    title: isDarkMode ? '#f8fafc' : '#1e293b',
                    tooltipBg: isDarkMode ? '#1e293b' : '#ffffff',
                    tooltipText: isDarkMode ? '#f8fafc' : '#1e293b'
                }
            };

            // Chart Configuration Factory
            function createChartConfig(type, data, options = {}) {
                return {
                    type: type,
                    data: {
                        labels: data.labels,
                        datasets: data.datasets.map((dataset, index) => ({
                            ...dataset,
                            backgroundColor: theme.colors.primary,
                            borderColor: theme.colors.primary,
                            borderWidth: 0,
                            borderRadius: 6
                        }))
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: type === 'pie' ? 'right' : 'top',
                                labels: { color: theme.colors.text }
                            }
                        },
                        scales: type === 'bar' ? {
                            x: { ticks: { color: theme.colors.text }, grid: { display: false } },
                            y: { ticks: { color: theme.colors.text }, grid: { color: theme.colors.grid, borderDash: [4, 4] } }
                        } : undefined,
                        ...options
                    }
                };
            }

            const chartData = @json($chartData);
            
            // Render Charts
            if(chartData.donors) new Chart(document.getElementById('donorNameChart'), createChartConfig('bar', chartData.donors));
            if(chartData.faculties) new Chart(document.getElementById('facultyChart'), createChartConfig('pie', chartData.faculties));
            if(chartData.departments) {
                new Chart(document.getElementById('departmentChart'), createChartConfig('pie', chartData.departments));
                new Chart(document.getElementById('departmentBarChart'), createChartConfig('bar', chartData.departments));
            }
            if(chartData.projects) {
                new Chart(document.getElementById('projectChart'), createChartConfig('pie', chartData.projects));
                new Chart(document.getElementById('projectBarChart'), createChartConfig('bar', chartData.projects));
            }
            if(chartData.states) new Chart(document.getElementById('stateChart'), createChartConfig('pie', chartData.states));
            if(chartData.lgas) new Chart(document.getElementById('lgaChart'), createChartConfig('bar', chartData.lgas));
            if(chartData.gender) new Chart(document.getElementById('genderChart'), createChartConfig('pie', chartData.gender));
        });
    </script>
</div>
