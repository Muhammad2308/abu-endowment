@extends('layouts.admin')

@section('title', 'Manage Donors')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Manage Donors</h2>
    </div>

    <!-- Alumni Upload Component -->
    <div class="mb-8">
        @livewire('admin.alumni-upload')
    </div>

    <!-- Donors Table Section -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Alumni List</h3>
                
                <!-- Add Donor Button -->
                <button onclick="Livewire.dispatch('open-add-donor-modal')" 
                        class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fas fa-plus mr-2"></i>Add Alumnus
                </button>
            </div>

            @livewire('admin.donors-table')
        </div>
    </div>
</div>

@livewire('admin.add-donor')
@endsection
