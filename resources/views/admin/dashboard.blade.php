@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div x-data="smsModal()" x-cloak x-init="window.addEventListener('open-sms-modal', e => { openModal(e.detail.phone); });">
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
            <!-- Livewire Alumni Upload Component -->
            @livewire('admin.alumni-upload')

            <!-- Faculty Management Card -->
            <a href="{{ route('admin.faculty.index') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-university text-cyan-600 dark:text-cyan-400 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Faculty Management</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Manage faculties and departments</p>
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
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Project Donations</h3>
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

            <a href="#" @click.prevent="open = true" class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
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

        <!-- Alumni Donors Table - Full Width -->
        <div class="w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Donors</h3>
                
                <!-- Add Donor Button -->
                <div class="flex justify-end mb-4">
                    <button onclick="Livewire.dispatch('open-add-donor-modal')" 
                            class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-plus mr-2"></i>Add Alumni
                    </button>
                </div>

                @livewire('admin.donors-table')
            </div>
        </div>
    </div>

    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button @click="open = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fas fa-times text-xl"></i>
            </button>
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Send SMS Notification</h2>
            <form @submit.prevent="sendSms()" class="flex flex-col gap-4 bg-gray-50 p-4 rounded-lg border">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="tel" x-model="phone" placeholder="e.g. +12345678901" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white" required pattern="^\+\d{10,15}$" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea x-model="message" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white" required maxlength="1600"></textarea>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="open = false" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700" x-bind:disabled="loading">
                        <span x-show="!loading">Send SMS</span>
                        <span x-show="loading">Sending...</span>
                    </button>
                </div>
                <div x-show="success" class="text-green-600 dark:text-green-400 text-sm mt-2" x-text="success"></div>
                <div x-show="error" class="text-red-600 dark:text-red-400 text-sm mt-2" x-text="error"></div>
            </form>
        </div>
    </div>
</div>

@livewire('admin.add-donor')
@endsection 

<script>
function smsModal() {
    return {
        open: false,
        phone: '',
        message: '',
        loading: false,
        success: '',
        error: '',
        openModal(phone = '') {
            this.phone = phone;
            this.message = '';
            this.success = '';
            this.error = '';
            this.open = true;
        },
        sendSms() {
            this.loading = true;
            this.success = '';
            this.error = '';
            fetch('/api/send-sms', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
                },
                body: JSON.stringify({
                    to: this.phone,
                    message: this.message
                })
            })
            .then(async res => {
                const data = await res.json();
                if (res.ok && data.success) {
                    this.success = 'Message sent!';
                    this.phone = '';
                    this.message = '';
                } else {
                    this.error = data.error || 'Failed to send SMS.';
                }
            })
            .catch(() => {
                this.error = 'Network error.';
            })
            .finally(() => {
                this.loading = false;
            });
        }
    }
}
</script> 