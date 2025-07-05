<div>
    @if ($showModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Add New Role</h3>
            <form wire:submit.prevent="saveRole">
                <div class="space-y-4">
                    <div>
                        <label for="role_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role Title</label>
                        <input type="text" wire:model.defer="role_title" id="role_title" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                        @error('role_title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="permission_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Permission</label>
                        <select wire:model.defer="permission_id" id="permission_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                            <option value="">Select Permission</option>
                            @if($permissions)
                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission->id }}">{{ $permission->permission_title }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('permission_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2" wire:click="closeModal">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Save Role</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div> 