<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Templates</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalTemplates }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300">
                    <i class="fas fa-paper-plane text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Emails Sent (Month)</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $emailsSentThisMonth }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300">
                    <i class="fas fa-exclamation-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Failed Emails</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $failedEmails }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.notifications.templates.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i>
                Create Email Template
            </a>
            <a href="{{ route('admin.notifications.send') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-paper-plane mr-2"></i>
                Send Notification
            </a>
            <a href="{{ route('admin.notifications.templates') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-list mr-2"></i>
                Manage Templates
            </a>
            <button @click="openSmsModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                <i class="fas fa-sms mr-2"></i>
                Send SMS
            </button>
            <a href="{{ route('admin.notifications.logs') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-history mr-2"></i>
                View Logs
            </a>
        </div>
    </div>

    <!-- SMS Modal -->
    <div x-data="smsModal()" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button @click="open = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fas fa-times text-xl"></i>
            </button>
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Send SMS Notification</h2>
            <form @submit.prevent="sendSms()" class="flex flex-col gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
                    <input type="tel" x-model="phone" placeholder="e.g. +12345678901" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message</label>
                    <textarea x-model="message" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white" required maxlength="160"></textarea>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="open = false" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-500">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 disabled:opacity-50" :disabled="loading">
                        <span x-show="!loading">Send SMS</span>
                        <span x-show="loading">Sending...</span>
                    </button>
                </div>
                <div x-show="success" class="text-green-600 dark:text-green-400 text-sm mt-2" x-text="success"></div>
                <div x-show="error" class="text-red-600 dark:text-red-400 text-sm mt-2" x-text="error"></div>
            </form>
        </div>
    </div>

    <script>
        function smsModal() {
            return {
                open: false,
                phone: '',
                message: '',
                loading: false,
                success: '',
                error: '',
                init() {
                    window.addEventListener('open-sms-modal', (e) => {
                        this.openSmsModal(e.detail.phone);
                    });
                },
                openSmsModal(phone = '') {
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
                            setTimeout(() => { this.open = false; }, 2000);
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
</div>
