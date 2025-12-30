@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div>
    <div class="px-4 py-6 sm:px-0">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Admin Dashboard</h2>
        
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users text-indigo-600 dark:text-indigo-400 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Donors</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $totalDonors }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-project-diagram text-green-600 dark:text-green-400 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Active Projects</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $activeProjects }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-money-bill-wave text-yellow-600 dark:text-yellow-400 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dlv>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Donations</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">₦ {{ number_format($totalDonations, 2) }}</dd>
                            </dlv>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-chart-line text-purple-600 dark:text-purple-400 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">This Month</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">₦ {{ number_format($totalDonationsThisMonth, 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Faculty Management Card -->
            <a href="{{ route('admin.faculty.index') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-university text-cyan-600 dark:text-cyan-400 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Faculty Management</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Manage faculties and visions</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Department Management Card -->
            <a href="{{ route('admin.department.index') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-building text-orange-600 dark:text-orange-400 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Departments/Programmes</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Manage departments and programmes</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Donor Management Card -->
            <a href="{{ route('admin.donors.index') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users text-blue-600 dark:text-blue-400 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Manage Donors</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">View and manage alumni and donors</p>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.projects') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-project-diagram text-green-600 dark:text-green-400 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Manage Projects</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Create and manage endowment projects</p>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.projects.donations') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-donate text-green-600 dark:text-green-400 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Projects/Donations</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">View all projects and their total donations</p>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.statistics') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-chart-bar text-purple-600 dark:text-purple-400 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Statistics</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">View donation analytics and reports</p>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.notifications.index') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-bell text-yellow-600 dark:text-yellow-400 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Notifications</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Send emails and SMS to donors</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- <a href="/api/documentation" target="_blank" class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-code text-blue-600 dark:text-blue-400 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">API Documentation</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">View available API endpoints</p>
                        </div>
                    </div>
                </div>
            </a> -->

            <a href="/" target="_blank" class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-home text-gray-600 dark:text-gray-400 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Public Site</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">View the public welcome page</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- User Management Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-6">
                    <a href="{{ route('admin.users.index') }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users-cog text-3xl text-green-500"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">User Management</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Manage users and roles</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-edit text-indigo-600 dark:text-indigo-400 text-3xl"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Profile</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Update your admin profile information</p>
                        </div>
                        <div>
                            <button onclick="Livewire.dispatch('open-profile-modal')" class="ml-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection 