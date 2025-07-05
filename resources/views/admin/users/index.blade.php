@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Management</h1>
    </div>

    <!-- Main Content -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">All Users</h3>
        </div>
        <div class="p-6">
            @livewire('admin.users-manager')
        </div>
    </div>
</div>

{{-- Modal Components --}}
@livewire('admin.add-user')
@livewire('admin.add-role')
{{-- @livewire('admin.edit-user') --}}

@endsection 