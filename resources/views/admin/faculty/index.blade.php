@extends('layouts.admin')

@section('title', 'Faculties Management')

@section('content')
<div x-data="{ activeTab: 'faculties' }" class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" role="tablist">
            <li class="mr-2" role="presentation">
                <button @click="activeTab = 'faculties'"
                        :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'faculties', 'border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300': activeTab !== 'faculties' }"
                        class="inline-block p-4 border-b-2 rounded-t-lg"
                        id="faculties-tab" type="button" role="tab" aria-controls="faculties" aria-selected="true">
                    Faculties
                </button>
            </li>
            <li class="mr-2" role="presentation">
                <button @click="activeTab = 'departments'"
                        :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'departments', 'border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300': activeTab !== 'departments' }"
                        class="inline-block p-4 border-b-2 rounded-t-lg"
                        id="departments-tab" type="button" role="tab" aria-controls="departments" aria-selected="false">
                    Departments
                </button>
            </li>
        </ul>
    </div>

    <div id="myTabContent">
        <div x-show="activeTab === 'faculties'" id="faculties" role="tabpanel" aria-labelledby="faculties-tab">
            <div class="mb-6 flex justify-between items-center">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Manage Faculties</h2>
                @livewire('admin.faculty-upload')
            </div>
            @livewire('admin.faculties-table')
        </div>
        <div x-show="activeTab === 'departments'" id="departments" role="tabpanel" aria-labelledby="departments-tab" style="display: none;">
            <div class="mb-6 flex justify-between items-center">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Manage Departments</h2>
                @livewire('admin.department-upload')
            </div>
            @livewire('admin.departments-table')
        </div>
    </div>

</div>
@endsection 