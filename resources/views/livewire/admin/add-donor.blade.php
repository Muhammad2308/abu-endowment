<div>
    @if ($showModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white dark:bg-gray-800">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Add New Donor</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" wire:click="closeModal">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form wire:submit.prevent="saveDonor">
                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 max-h-[70vh] overflow-y-auto pr-2">
                    <!-- Surname -->
                    <div class="col-span-1">
                        <label for="surname" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Surname</label>
                        <input type="text" wire:model.defer="surname" id="surname" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                        @error('surname') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Name -->
                    <div class="col-span-1">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">First Name</label>
                        <input type="text" wire:model.defer="name" id="name" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Other Name -->
                    <div class="col-span-1">
                        <label for="other_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Other Name (Optional)</label>
                        <input type="text" wire:model.defer="other_name" id="other_name" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                        @error('other_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Gender -->
                    <div class="col-span-1">
                        <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gender (Optional)</label>
                        <select wire:model.defer="gender" id="gender" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        @error('gender') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Reg Number -->
                    <div class="col-span-1">
                        <label for="reg_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Registration Number</label>
                        <input type="text" wire:model.defer="reg_number" id="reg_number" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                        @error('reg_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-span-1">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                        <input type="email" wire:model.defer="email" id="email" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Phone -->
                    <div class="col-span-1">
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                        <input type="tel" wire:model.defer="phone" id="phone" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                        @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Address -->
                    <div class="col-span-3">
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                        <textarea wire:model.defer="address" id="address" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white"></textarea>
                        @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Nationality -->
                    <div class="col-span-1">
                        <label for="nationality" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nationality</label>
                        <input type="text" wire:model.defer="nationality" id="nationality" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                        @error('nationality') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- State -->
                    <div class="col-span-1">
                        <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">State</label>
                        <input type="text" wire:model.defer="state" id="state" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                        @error('state') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- LGA -->
                    <div class="col-span-1">
                        <label for="lga" class="block text-sm font-medium text-gray-700 dark:text-gray-300">LGA</label>
                        <input type="text" wire:model.defer="lga" id="lga" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                        @error('lga') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Entry Year -->
                    <div class="col-span-1">
                        <label for="entry_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Entry Year</label>
                        <input type="number" wire:model.defer="entry_year" id="entry_year" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white" placeholder="YYYY">
                        @error('entry_year') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Graduation Year -->
                    <div class="col-span-1">
                        <label for="graduation_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Graduation Year</label>
                        <input type="number" wire:model.defer="graduation_year" id="graduation_year" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white" placeholder="YYYY">
                        @error('graduation_year') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Faculty -->
                    <div class="col-span-3">
                        <label for="faculty_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Faculty</label>
                        <select wire:model.live="faculty_id" id="faculty_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                            <option value="">Select Faculty</option>
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->id }}">{{ $faculty->current_name }}</option>
                            @endforeach
                        </select>
                        @error('faculty_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Department -->
                    <div class="col-span-3">
                        <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Department</label>
                        <select wire:model.defer="department_id" id="department_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-white" @if(empty($departments)) disabled @endif>
                            <option value="">Select Department</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->current_name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end items-center pt-4 border-t border-gray-200 dark:border-gray-700 mt-6">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 mr-2" wire:click="closeModal">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 disabled:opacity-50" wire:loading.attr="disabled">
                        <span wire:loading wire:target="saveDonor" class="inline-block animate-spin mr-2"><i class="fas fa-spinner"></i></span>
                        Save Donor
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div> 