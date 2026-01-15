<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-50 text-indigo-600">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-bold text-slate-500">Total Templates</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalTemplates }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-emerald-50 text-emerald-600">
                    <i class="fas fa-paper-plane text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-bold text-slate-500">Emails Sent (Month)</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $emailsSentThisMonth }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-rose-50 text-rose-600">
                    <i class="fas fa-exclamation-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-bold text-slate-500">Failed Emails</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $failedEmails }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.notifications.templates.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Create Email Template
            </a>
            <a href="{{ route('admin.notifications.send') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                <i class="fas fa-paper-plane mr-2"></i>
                Send Notification
            </a>
            <a href="{{ route('admin.notifications.templates') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <i class="fas fa-list mr-2"></i>
                Manage Templates
            </a>
            <button @click="openSmsModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                <i class="fas fa-sms mr-2"></i>
                Send SMS
            </button>
            <a href="{{ route('admin.notifications.logs') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <i class="fas fa-history mr-2"></i>
                View Logs
            </a>
        </div>
    </div>

    <!-- SMS Modal -->
    <div x-data="smsModal()" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" x-cloak>
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 w-full max-w-md flex flex-col relative overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h2 class="text-lg font-bold text-slate-800">Send SMS Notification</h2>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                <form @submit.prevent="sendSms()" class="flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Phone Number</label>
                        <input type="tel" x-model="phone" placeholder="e.g. +12345678901" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" required />
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Message</label>
                        <textarea x-model="message" rows="4" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" required maxlength="160"></textarea>
                        <p class="text-xs text-slate-400 text-right mt-1" x-text="message.length + '/160'"></p>
                    </div>
                    <div class="flex justify-end gap-3 mt-2">
                        <button type="button" @click="open = false" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm" :disabled="loading">
                            <span x-show="!loading">Send SMS</span>
                            <span x-show="loading"><i class="fas fa-spinner fa-spin mr-2"></i>Sending...</span>
                        </button>
                    </div>
                    <div x-show="success" class="p-3 bg-emerald-50 text-emerald-700 rounded-lg text-sm border border-emerald-100 flex items-center" x-text="success"></div>
                    <div x-show="error" class="p-3 bg-rose-50 text-rose-700 rounded-lg text-sm border border-rose-100 flex items-center" x-text="error"></div>
                </form>
            </div>
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
