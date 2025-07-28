<div>
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Alumni Records</h3>
        <input wire:model.live="search" type="text" placeholder="Search donors..." class="w-1/3 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                        Contact
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                        Academic Info
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                        Faculty/Department
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                        Location
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                        Donations
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                @forelse($donors as $donor)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $donor->surname }} {{ $donor->name }}
                                @if($donor->other_name)
                                    <span class="text-gray-500 dark:text-gray-400">({{ $donor->other_name }})</span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $donor->reg_number }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $donor->email }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $donor->phone }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $donor->entry_year }} - {{ $donor->graduation_year }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $donor->graduation_year - $donor->entry_year }} years
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $donor->faculty->current_name ?? 'N/A' }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $donor->department->current_name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $donor->state }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $donor->lga }}, {{ $donor->nationality }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            @if($donor->donations->count() === 0)
                                <span class="inline-block px-3 py-1 text-xs font-semibold bg-gray-200 text-gray-700 rounded-full">None</span>
                            @else
                                <button wire:click="showDonations({{ $donor->id }})" class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full hover:bg-indigo-200 focus:outline-none">
                                    <span class="mr-2">Details</span>
                                    <span class="inline-block bg-indigo-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $donor->donations->count() }}</span>
                                </button>
                            @endif
                            <button 
                                @click.prevent="window.dispatchEvent(new CustomEvent('open-sms-modal', { detail: { phone: '{{ $donor->phone }}' } }))"
                                class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full hover:bg-green-200 focus:outline-none ml-2"
                            >
                                <i class="fas fa-sms mr-1"></i> SMS
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No donors found. Try adjusting your search or upload some alumni data.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $donors->links() }}
    </div>

    @if($showDonationsModal && $selectedDonor)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-data="{ show: @entangle('showDonationsModal'), showWhatsAppForm: false }" x-init="window.addEventListener('message-sent', () => { showWhatsAppForm = false })" x-show="show" x-cloak>
            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl max-h-[80vh] flex flex-col">
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold">Donations for {{ $selectedDonor->surname }} {{ $selectedDonor->name }}</h3>
                    <button @click="$wire.set('showDonationsModal', false)" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
                </div>
                <div class="overflow-y-auto p-6 flex-1">
                    <div class="mb-4 font-bold text-indigo-700 text-lg">
                        Total Donations: ₦{{ number_format($selectedDonor->donations->sum('amount'), 2) }}
                    </div>
                    <!-- Message Toggle Button -->
                    <div class="mb-4">
                        <button @click="showWhatsAppForm = !showWhatsAppForm"
                                class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 focus:outline-none">
                            <i class="fas fa-envelope mr-2"></i>
                            <span x-text="showWhatsAppForm ? 'Hide Message Form' : 'Send Message'"></span>
                        </button>
                    </div>
                    <!-- Message Form -->
                    <div x-show="showWhatsAppForm" x-cloak class="mb-6">
                        <form wire:submit.prevent="sendMessage" class="flex flex-col gap-4 bg-gray-50 p-4 rounded-lg border">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                <input type="text" wire:model.defer="messageSubject" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                <textarea wire:model.defer="messageBody" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                            </div>
                            <div class="flex justify-end gap-2 mt-4">
                                <button type="button" @click="showWhatsAppForm = false" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Send</button>
                            </div>
                        </form>
                    </div>
                    <!-- End Message Form -->
                    @forelse($selectedDonations as $donation)
                        <div class="mb-4 p-4 border rounded-lg bg-gray-50">
                            <div class="font-semibold text-indigo-700">Amount: ₦{{ number_format($donation->amount, 2) }}</div>
                            <div class="text-sm text-gray-700">Date: {{ $donation->created_at->format('M d, Y H:i') }}</div>
                            <div class="text-sm text-gray-700">Project: {{ $donation->project->name ?? 'Endowment' }}</div>
                            <div class="text-sm text-gray-500">Reference: {{ $donation->payment_reference }}</div>
                        </div>
                    @empty
                        <div class="text-gray-500">No donations found for this donor.</div>
                    @endforelse
                </div>
                <div class="flex justify-end gap-2 px-6 py-4 border-t">
                    <button @click="$wire.set('showDonationsModal', false)" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Close</button>
                </div>
            </div>
        </div>
    @endif
</div>
