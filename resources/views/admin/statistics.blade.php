@extends('layouts.admin')

@section('title', 'View Statistics')

@section('content')
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Donation Statistics</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Donor Name Card -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">By Donor Name</h3>
            <canvas id="donorNameChart" height="200"></canvas>
        </div>
        <!-- Donor Type Card -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">By Donor Type</h3>
            <canvas id="donorTypeChart" height="200"></canvas>
        </div>
        <!-- Department Card (Alumni only) -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">By Department (Alumni)</h3>
            <canvas id="departmentChart" height="200"></canvas>
        </div>
        <!-- State Card -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">By State</h3>
            <canvas id="stateChart" height="200"></canvas>
        </div>
        <!-- LGA Card -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">By LGA</h3>
            <canvas id="lgaChart" height="200"></canvas>
        </div>
        <!-- Gender Card -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">By Gender</h3>
            <canvas id="genderChart" height="200"></canvas>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Placeholder data for demonstration
        const donorNameData = {
            labels: ['John Doe', 'Jane Smith', 'Aliyu Musa'],
            datasets: [{
                label: 'Total Donations',
                data: [5000, 3000, 2000],
                backgroundColor: ['#6366f1', '#f59e42', '#10b981'],
            }]
        };
        const donorTypeData = {
            labels: ['Alumni', 'Non-Alumni'],
            datasets: [{
                data: [60, 40],
                backgroundColor: ['#6366f1', '#f59e42'],
            }]
        };
        const departmentData = {
            labels: ['Engineering', 'Sciences', 'Arts', 'Law'],
            datasets: [{
                data: [20, 25, 10, 5],
                backgroundColor: ['#6366f1', '#f59e42', '#10b981', '#f43f5e'],
            }]
        };
        const stateData = {
            labels: ['Kaduna', 'Lagos', 'Kano', 'Others'],
            datasets: [{
                data: [30, 20, 10, 40],
                backgroundColor: ['#6366f1', '#f59e42', '#10b981', '#f43f5e'],
            }]
        };
        const lgaData = {
            labels: ['LGA1', 'LGA2', 'LGA3', 'Others'],
            datasets: [{
                data: [15, 25, 20, 40],
                backgroundColor: ['#6366f1', '#f59e42', '#10b981', '#f43f5e'],
            }]
        };
        const genderData = {
            labels: ['Male', 'Female'],
            datasets: [{
                data: [55, 45],
                backgroundColor: ['#6366f1', '#f43f5e'],
            }]
        };
        new Chart(document.getElementById('donorNameChart'), { type: 'bar', data: donorNameData });
        new Chart(document.getElementById('donorTypeChart'), { type: 'pie', data: donorTypeData });
        new Chart(document.getElementById('departmentChart'), { type: 'bar', data: departmentData });
        new Chart(document.getElementById('stateChart'), { type: 'pie', data: stateData });
        new Chart(document.getElementById('lgaChart'), { type: 'bar', data: lgaData });
        new Chart(document.getElementById('genderChart'), { type: 'pie', data: genderData });
    </script>
@endsection 