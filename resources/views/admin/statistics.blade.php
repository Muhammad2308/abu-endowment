@extends('layouts.admin')

@section('title', 'View Statistics')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Donation Statistics</h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Comprehensive overview of donation patterns and trends</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <button id="refreshBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh Data
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Donations</dt>
                                <dd class="text-lg font-semibold text-gray-900 dark:text-white">₦10,000</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Donors</dt>
                                <dd class="text-lg font-semibold text-gray-900 dark:text-white">3</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Alumni Donors</dt>
                                <dd class="text-lg font-semibold text-gray-900 dark:text-white">60%</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Avg. Donation</dt>
                                <dd class="text-lg font-semibold text-gray-900 dark:text-white">₦3,333</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-2">Loading Statistics</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Please wait while we fetch the latest data...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            <!-- Donor Name Chart -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top Donors</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Donation amounts by individual donors</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                Bar Chart
                            </span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="relative h-80">
                        <canvas id="donorNameChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Donor Type Chart -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Donor Categories</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Distribution between Alumni and Non-Alumni</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Pie Chart
                            </span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="relative h-80">
                        <canvas id="donorTypeChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Department Chart -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Alumni by Department</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Donation patterns across different departments</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                Bar Chart
                            </span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="relative h-80">
                        <canvas id="departmentChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- State Chart -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Geographic Distribution</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Donations by state of origin</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                Pie Chart
                            </span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="relative h-80">
                        <canvas id="stateChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- LGA Chart -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Local Government Areas</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Detailed geographic breakdown</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                Bar Chart
                            </span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="relative h-80">
                        <canvas id="lgaChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gender Chart -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Gender Distribution</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Donor participation by gender</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200">
                                Pie Chart
                            </span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="relative h-80">
                        <canvas id="genderChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Global chart configuration for consistent theming
        Chart.defaults.responsive = true;
        Chart.defaults.maintainAspectRatio = false;
        Chart.defaults.plugins.legend.labels.usePointStyle = true;
        Chart.defaults.plugins.legend.labels.padding = 20;
        
        // Dark mode detection
        const isDarkMode = document.documentElement.classList.contains('dark');
        
        // Color schemes for consistent theming
        const colorSchemes = {
            primary: ['#6366f1', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b', '#ef4444'],
            light: {
                text: '#374151',
                grid: '#e5e7eb',
                background: '#ffffff'
            },
            dark: {
                text: '#d1d5db',
                grid: '#374151',
                background: '#1f2937'
            }
        };
        
        const currentTheme = isDarkMode ? colorSchemes.dark : colorSchemes.light;
        
        // Enhanced chart configuration factory
        function createChartConfig(type, data, options = {}) {
            const baseConfig = {
                type: type,
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            position: type === 'pie' ? 'right' : 'top',
                            labels: {
                                color: currentTheme.text,
                                font: {
                                    size: 12,
                                    weight: '500'
                                },
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            backgroundColor: isDarkMode ? '#1f2937' : '#ffffff',
                            titleColor: currentTheme.text,
                            bodyColor: currentTheme.text,
                            borderColor: currentTheme.grid,
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    if (type === 'pie') {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.parsed * 100) / total).toFixed(1);
                                        return `${context.label}: ${percentage}%`;
                                    }
                                    return `${context.dataset.label}: ₦${context.parsed.y?.toLocaleString() || context.parsed.toLocaleString()}`;
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    },
                    ...options
                }
            };
            
            // Add scales for bar charts
            if (type === 'bar') {
                baseConfig.options.scales = {
                    x: {
                        grid: {
                            color: currentTheme.grid,
                            borderColor: currentTheme.grid
                        },
                        ticks: {
                            color: currentTheme.text,
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: currentTheme.grid,
                            borderColor: currentTheme.grid
                        },
                        ticks: {
                            color: currentTheme.text,
                            font: {
                                size: 11
                            },
                            callback: function(value) {
                                return '₦' + value.toLocaleString();
                            }
                        }
                    }
                };
            }
            
            return baseConfig;
        }
        
        // Loading state management
        function showLoading() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
        }
        
        function hideLoading() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        }
        
        // Error handling
        function showError(message) {
            console.error('Statistics Error:', message);
            // You can implement a toast notification system here
        }
        
        // Enhanced data with better formatting
        const statisticsData = {
            donorNameData: {
                labels: ['John Doe', 'Jane Smith', 'Aliyu Musa', 'Fatima Abubakar', 'Ahmed Ibrahim'],
                datasets: [{
                    label: 'Total Donations',
                    data: [5000, 3000, 2000, 1500, 1000],
                    backgroundColor: colorSchemes.primary.slice(0, 5),
                    borderColor: colorSchemes.primary.slice(0, 5),
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false,
                }]
            },
            donorTypeData: {
                labels: ['Alumni', 'Non-Alumni'],
                datasets: [{
                    data: [60, 40],
                    backgroundColor: ['#6366f1', '#f59e0b'],
                    borderColor: ['#4f46e5', '#d97706'],
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            departmentData: {
                labels: ['Engineering', 'Sciences', 'Arts', 'Law', 'Medicine'],
                datasets: [{
                    label: 'Number of Donors',
                    data: [25, 20, 15, 10, 8],
                    backgroundColor: colorSchemes.primary.slice(0, 5),
                    borderColor: colorSchemes.primary.slice(0, 5),
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false,
                }]
            },
            stateData: {
                labels: ['Kaduna', 'Lagos', 'Kano', 'Abuja', 'Others'],
                datasets: [{
                    data: [30, 25, 15, 20, 10],
                    backgroundColor: colorSchemes.primary.slice(0, 5),
                    borderColor: colorSchemes.primary.slice(0, 5),
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            lgaData: {
                labels: ['Zaria', 'Sabon Gari', 'Ikeja', 'Fagge', 'Others'],
                datasets: [{
                    label: 'Number of Donors',
                    data: [20, 15, 12, 8, 15],
                    backgroundColor: colorSchemes.primary.slice(0, 5),
                    borderColor: colorSchemes.primary.slice(0, 5),
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false,
                }]
            },
            genderData: {
                labels: ['Male', 'Female'],
                datasets: [{
                    data: [55, 45],
                    backgroundColor: ['#6366f1', '#ec4899'],
                    borderColor: ['#4f46e5', '#db2777'],
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            }
        };
        
        // Chart instances storage
        let charts = {};
        
        // API base URL
        const API_BASE = '/api/statistics';
        
        // Fetch real statistics data from API
        async function fetchStatisticsData() {
            try {
                showLoading();
                
                const endpoints = [
                    `${API_BASE}/donors`,
                    `${API_BASE}/donor-types`,
                    `${API_BASE}/departments`,
                    `${API_BASE}/states`,
                    `${API_BASE}/lgas`,
                    `${API_BASE}/gender`,
                    `${API_BASE}/summary`
                ];
                
                const responses = await Promise.all(
                    endpoints.map(url => fetch(url).then(r => r.json()))
                );
                
                const [donorsData, donorTypesData, departmentsData, statesData, lgasData, genderData, summaryData] = responses;
                
                // Update summary cards
                if (summaryData.success) {
                    updateSummaryCards(summaryData.data);
                }
                
                // Create charts with real data
                if (donorsData.success) {
                    charts.donorName = new Chart(
                        document.getElementById('donorNameChart'),
                        createChartConfig('bar', donorsData.data)
                    );
                }
                
                if (donorTypesData.success) {
                    charts.donorType = new Chart(
                        document.getElementById('donorTypeChart'),
                        createChartConfig('pie', donorTypesData.data)
                    );
                }
                
                if (departmentsData.success) {
                    charts.department = new Chart(
                        document.getElementById('departmentChart'),
                        createChartConfig('bar', departmentsData.data)
                    );
                }
                
                if (statesData.success) {
                    charts.state = new Chart(
                        document.getElementById('stateChart'),
                        createChartConfig('pie', statesData.data)
                    );
                }
                
                if (lgasData.success) {
                    charts.lga = new Chart(
                        document.getElementById('lgaChart'),
                        createChartConfig('bar', lgasData.data)
                    );
                }
                
                if (genderData.success) {
                    charts.gender = new Chart(
                        document.getElementById('genderChart'),
                        createChartConfig('pie', genderData.data)
                    );
                }
                
                hideLoading();
            } catch (error) {
                console.error('API Error:', error);
                hideLoading();
                // Fall back to placeholder data if API fails
                initializeFallbackCharts();
                showError('Unable to fetch live data. Showing sample data.');
            }
        }
        
        // Update summary cards with real data
        function updateSummaryCards(data) {
            const cards = document.querySelectorAll('.grid .bg-white.dark\\:bg-gray-800 dd');
            if (cards.length >= 4) {
                cards[0].textContent = `₦${data.total_donations.toLocaleString()}`;
                cards[1].textContent = data.total_donors.toLocaleString();
                cards[2].textContent = `${data.alumni_percentage}%`;
                cards[3].textContent = `₦${data.average_donation.toLocaleString()}`;
            }
        }
        
        // Initialize charts with fallback data if API fails
        function initializeFallbackCharts() {
            try {
                // Destroy existing charts if they exist
                Object.values(charts).forEach(chart => {
                    if (chart) chart.destroy();
                });
                
                // Create new charts with fallback data
                charts.donorName = new Chart(
                    document.getElementById('donorNameChart'),
                    createChartConfig('bar', statisticsData.donorNameData)
                );
                
                charts.donorType = new Chart(
                    document.getElementById('donorTypeChart'),
                    createChartConfig('pie', statisticsData.donorTypeData)
                );
                
                charts.department = new Chart(
                    document.getElementById('departmentChart'),
                    createChartConfig('bar', statisticsData.departmentData)
                );
                
                charts.state = new Chart(
                    document.getElementById('stateChart'),
                    createChartConfig('pie', statisticsData.stateData)
                );
                
                charts.lga = new Chart(
                    document.getElementById('lgaChart'),
                    createChartConfig('bar', statisticsData.lgaData)
                );
                
                charts.gender = new Chart(
                    document.getElementById('genderChart'),
                    createChartConfig('pie', statisticsData.genderData)
                );
                
            } catch (error) {
                showError('Failed to initialize charts: ' + error.message);
            }
        }
        
        // Initialize charts function (now uses API)
        function initializeCharts() {
            fetchStatisticsData();
        }
        
        // Refresh data function
        function refreshCharts() {
            // Destroy existing charts
            Object.values(charts).forEach(chart => {
                if (chart) chart.destroy();
            });
            charts = {};
            
            // Fetch fresh data from API
            fetchStatisticsData();
        }
        
        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            
            // Refresh button functionality
            document.getElementById('refreshBtn').addEventListener('click', refreshCharts);
            
            // Dark mode toggle support (if you implement it)
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        // Reinitialize charts when dark mode changes
                        setTimeout(initializeCharts, 100);
                    }
                });
            });
            
            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
        });
        
        // Responsive chart resize handler
        window.addEventListener('resize', function() {
            Object.values(charts).forEach(chart => {
                if (chart) {
                    chart.resize();
                }
            });
        });
    </script>
@endsection 