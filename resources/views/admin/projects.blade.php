@extends('layouts.admin')

@section('title', 'Projects Management')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Projects Management</h1>
    </div>

    <!-- Action Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Add Project Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wide">
                            Add Project
                        </div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">Create New</div>
                    </div>
                    <div class="col-auto">
                        <button onclick="Livewire.dispatch('open-add-project-modal')" 
                                class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg transition-colors duration-200">
                            <i class="fas fa-plus text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Projects Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-green-600 dark:text-green-400 uppercase tracking-wide">
                            Upload Projects
                        </div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">Excel Import</div>
                    </div>
                    <div>
                        <button onclick="Livewire.dispatch('open-project-upload-modal')" 
                                class="bg-green-600 hover:bg-green-700 text-white p-3 rounded-lg transition-colors duration-200">
                            <i class="fas fa-upload text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Table -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Projects List</h3>
        </div>
        <div class="p-6">
            @livewire('admin.projects-manager')
        </div>
    </div>
</div>

<!-- Test Modal -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="testModal" style="display: none;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Test Modal</h3>
            <p class="mt-2 text-sm text-gray-500">This is a test modal to see if modals work.</p>
            <div class="flex items-center justify-end mt-4">
                <button onclick="document.getElementById('testModal').style.display='none'" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Include Livewire Components -->
@livewire('admin.add-project')
@livewire('admin.project-upload')
@livewire('admin.add-project-photos')
@livewire('admin.view-project-details')

<!-- Flash Messages -->
@if (session()->has('message'))
    <div class="fixed top-4 right-4 z-50 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg" role="alert">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>{{ session('message') }}</span>
            <button type="button" class="ml-4 text-green-700 hover:text-green-900" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

@if (session()->has('error'))
    <div class="fixed top-4 right-4 z-50 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg" role="alert">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="ml-4 text-red-700 hover:text-red-900" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

@endsection 