@extends('layouts.admin')

@section('title', 'Donations Overview')

@section('content')

    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Donations Overview</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View all donations with project details, targets, and raised amounts</p>
    </div>

    @livewire('admin.donations-table')

@endsection

