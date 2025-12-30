<div class="bg-white shadow rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">{{ $templateId ? 'Edit Template' : 'Create Template' }}</h2>
        <a href="{{ route('admin.notifications.templates') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Template Name</label>
                <input type="text" id="name" wire:model.lazy="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                <input type="text" id="slug" wire:model.defer="slug" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700">Email Subject</label>
                <div class="flex">
                    <input type="text" id="subject" wire:model.defer="subject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <select onchange="insertVariableAtCursor(document.getElementById('subject'), this.value); @this.set('subject', document.getElementById('subject').value)" class="ml-2 mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
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
                @error('subject') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div wire:ignore>
                <label for="body_html" class="block text-sm font-medium text-gray-700">Email Body</label>
                <div class="mb-2">
                     <select id="variableSelect" class="block w-full md:w-auto rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
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

            <div class="flex items-center">
                <input type="checkbox" id="is_active" wire:model="is_active" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i> Save Template
                </button>
            </div>
        </div>
    </form>

    <script>
        function insertVariableAtCursor(input, value) {
            if (!value) return;
            const start = input.selectionStart;
            const end = input.selectionEnd;
            const text = input.value;
            const before = text.substring(0, start);
            const after = text.substring(end, text.length);
            input.value = before + value + after;
            input.selectionStart = input.selectionEnd = start + value.length;
            input.focus();
        }

        document.addEventListener('livewire:initialized', function () {
            const trixEditor = document.querySelector('trix-editor');
            
            trixEditor.addEventListener('trix-change', function (e) {
                @this.set('body_html', e.target.value);
            });

            document.getElementById('variableSelect').addEventListener('change', function(e) {
                if (e.target.value) {
                    trixEditor.editor.insertString(e.target.value);
                    e.target.value = ''; // Reset select
                }
            });
        });
    </script>
</div>
