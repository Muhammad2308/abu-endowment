<div class="bg-white shadow rounded-lg p-4 sm:p-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
        <h2 class="text-xl font-semibold text-gray-800">{{ $templateId ? 'Edit Template' : 'Create Template' }}</h2>
        <a href="{{ route('admin.notifications.templates') }}" class="text-gray-600 hover:text-gray-900 text-sm flex items-center gap-1 self-start sm:self-auto">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <form wire:submit.prevent="save" class="space-y-5">

        {{-- Row 1: Name + Slug --}}
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Template Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" wire:model.lazy="name"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="e.g. Donation Thank You">
                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex-1">
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-red-500">*</span></label>
                <input type="text" id="slug" wire:model.defer="slug"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="auto-generated from name">
                @error('slug') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Row 2: Subject + Donor Tier --}}
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Email Subject <span class="text-red-500">*</span></label>
                <div class="flex gap-2">
                    <input type="text" id="subject" wire:model.defer="subject"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="e.g. Thank you for your donation">
                    <select onchange="insertVariableAtCursor(document.getElementById('subject'), this.value); @this.set('subject', document.getElementById('subject').value)"
                        class="shrink-0 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-xs">
                        <option value="">Insert Variable</option>
                        <option value="{!! '{{donor_name}}' !!}">Donor Name</option>
                        <option value="{!! '{{donor_email}}' !!}">Donor Email</option>
                        <option value="{!! '{{amount}}' !!}">Amount</option>
                        <option value="{!! '{{donation_date}}' !!}">Donation Date</option>
                        <option value="{!! '{{reference}}' !!}">Reference</option>
                        <option value="{!! '{{project_name}}' !!}">Project Name</option>
                        <option value="{!! '{{organization_name}}' !!}">Organization Name</option>
                    </select>
                </div>
                @error('subject') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="lg:w-64">
                <label for="donor_tier_id" class="block text-sm font-medium text-gray-700 mb-1">Target Donor Tier</label>
                <select id="donor_tier_id" wire:model="donor_tier_id"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">— All Donors —</option>
                    @foreach($tiers as $tier)
                        <option value="{{ $tier->id }}">
                            {{ $tier->name }}
                            (₦{{ number_format($tier->min_amount) }}{{ $tier->max_amount ? ' – ₦' . number_format($tier->max_amount) : '+' }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Leave blank to target all donors regardless of tier.</p>
            </div>
        </div>

        {{-- Row 3: Email Body (full width) --}}
        <div wire:ignore>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                <label class="block text-sm font-medium text-gray-700">Email Body <span class="text-red-500">*</span></label>
                <select id="variableSelect"
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-xs self-start sm:self-auto">
                    <option value="">Insert Variable into Editor</option>
                    <option value="{!! '{{donor_name}}' !!}">Donor Name</option>
                    <option value="{!! '{{donor_email}}' !!}">Donor Email</option>
                    <option value="{!! '{{amount}}' !!}">Amount</option>
                    <option value="{!! '{{donation_date}}' !!}">Donation Date</option>
                    <option value="{!! '{{reference}}' !!}">Reference</option>
                    <option value="{!! '{{project_name}}' !!}">Project Name</option>
                    <option value="{!! '{{organization_name}}' !!}">Organization Name</option>
                </select>
            </div>
            <input id="body_html" type="hidden" name="content" value="{{ $body_html }}">
            <trix-editor input="body_html" class="trix-content min-h-[300px] border border-gray-300 rounded-md"></trix-editor>
        </div>
        @error('body_html') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

        {{-- Row 4: Active + Save --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" id="is_active" wire:model="is_active"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <span class="text-sm text-gray-900">Active</span>
            </label>

            <button type="submit"
                class="inline-flex items-center justify-center gap-2 px-5 py-2 rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full sm:w-auto">
                <i class="fas fa-save"></i> Save Template
            </button>
        </div>

    </form>

    <script>
        function insertVariableAtCursor(input, value) {
            if (!value) return;
            const start = input.selectionStart;
            const end = input.selectionEnd;
            const text = input.value;
            input.value = text.substring(0, start) + value + text.substring(end);
            input.selectionStart = input.selectionEnd = start + value.length;
            input.focus();
        }

        document.addEventListener('livewire:initialized', function () {
            const trixEditor = document.querySelector('trix-editor');

            trixEditor.addEventListener('trix-change', function (e) {
                @this.set('body_html', e.target.value);
            });

            document.getElementById('variableSelect').addEventListener('change', function (e) {
                if (e.target.value) {
                    trixEditor.editor.insertString(e.target.value);
                    e.target.value = '';
                }
            });
        });
    </script>
</div>
