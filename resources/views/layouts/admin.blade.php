<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - ABU Endowment</title>
    <link rel="icon" href="{{ asset('icon/favicon-32x32.png') }}" type="image/png">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Flowbite -->
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.1/dist/flowbite.min.css" />
    
    <!-- Trix Editor -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    
    @livewireStyles
    
    <style>
        /* Ensure dropdowns appear above all other content */
        .dropdown-menu, [x-show] {
            z-index: 9999 !important;
        }
        
        /* Smooth transitions for sidebar navigation */
        .nav-link {
            transition: all 0.2s ease-in-out;
        }
        
        /* Custom scrollbar for sidebar */
        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Dark mode scrollbar */
        .dark .overflow-y-auto::-webkit-scrollbar-track {
            background: #374151;
        }
        
        .dark .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #6b7280;
        }
        
        .dark .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        /* Trix Editor Dark Mode - Global Inversion Fix */
        .dark trix-toolbar {
            background-color: #ffffff !important;
            filter: invert(1) hue-rotate(180deg) !important;
            border-radius: 0.5rem !important;
            padding: 0.25rem !important;
            margin-bottom: 0.5rem !important;
        }
        .dark trix-toolbar .trix-button-group {
            border: 1px solid #e5e7eb !important;
            background-color: #ffffff !important;
        }
        .dark trix-toolbar .trix-button {
            background-color: #ffffff !important;
            border-right: 1px solid #e5e7eb !important;
        }
        .dark trix-toolbar .trix-button:hover {
            background-color: #f3f4f6 !important;
        }
        .dark trix-toolbar .trix-button--active {
            background-color: #e5e7eb !important;
        }
        .dark trix-editor {
            color: #f3f4f6 !important;
            background-color: #1f2937 !important;
            border: 1px solid #374151 !important;
        }
        .dark trix-editor:focus {
            border-color: #6366f1 !important;
        }
        .dark .trix-content {
            color: #f3f4f6 !important;
        }
        .dark trix-toolbar .trix-dialog {
            filter: invert(1) hue-rotate(180deg) !important;
            background-color: #374151 !important;
            border: 1px solid #4b5563 !important;
            color: #f3f4f6 !important;
        }
        .dark trix-toolbar .trix-dialog input {
            background-color: #1f2937 !important;
            border: 1px solid #4b5563 !important;
            color: #f3f4f6 !important;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900" x-data="{ sidebarOpen: false }">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Mobile menu button -->
                    <div class="lg:hidden">
                        <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                            <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="flex-shrink-0 ml-4 lg:ml-0">
                        <div class="flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-indigo-600 dark:text-indigo-400 flex items-center">
                                <img src="{{ asset('icon/Header.png') }}" alt="ABU Endowment" class="h-12 w-auto mr-3">
                                <span class="hidden sm:block">ABU Endowment Admin</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ profileOpen: false }" @click.away="profileOpen = false">
                        <button @click="profileOpen = !profileOpen" class="flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-full">
                            <i class="fas fa-user-circle text-2xl text-indigo-600 dark:text-indigo-400"></i>
                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="profileOpen" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <a href="#" 
                                   @click.prevent="$dispatch('open-profile-modal'); profileOpen = false" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-user mr-3 text-indigo-600 dark:text-indigo-400"></i>
                                    Profile Settings
                                </a>
                                <div class="border-t border-gray-100 dark:border-gray-700"></div>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <i class="fas fa-sign-out-alt mr-3 text-red-600 dark:text-red-400"></i>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar Navigation -->
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75 lg:hidden z-40"></div>
        
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 shadow-lg transform lg:transform-none lg:static lg:inset-0 z-50 transition duration-300 ease-in-out" :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" @click.away="sidebarOpen = false">
            <div class="flex flex-col h-full">
                <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                    <nav class="mt-5 flex-1 px-2 space-y-1">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900 dark:text-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                            <i class="fas fa-tachometer-alt mr-3 flex-shrink-0 h-5 w-5"></i>
                            Dashboard
                        </a>

                        <!-- Faculty Management -->
                        <a href="{{ route('admin.faculty.index') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.faculty.*') ? 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900 dark:text-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                            <i class="fas fa-university mr-3 flex-shrink-0 h-5 w-5"></i>
                            Faculties
                        </a>

                        <!-- Department Management -->
                        <a href="{{ route('admin.department.index') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.department.*') ? 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900 dark:text-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                            <i class="fas fa-building mr-3 flex-shrink-0 h-5 w-5"></i>
                            Departments
                        </a>

                        <!-- Donor Management -->
                        <a href="{{ route('admin.donors.index') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.donors.*') ? 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900 dark:text-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                            <i class="fas fa-users mr-3 flex-shrink-0 h-5 w-5"></i>
                            Donors
                        </a>

                        <!-- Projects Management -->
                        <div class="space-y-1" x-data="{ projectsOpen: {{ request()->routeIs('admin.projects*') || request()->routeIs('admin.project-categories*') ? 'true' : 'false' }} }">
                            <div class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 dark:text-gray-300">
                                <button @click="projectsOpen = !projectsOpen" class="flex items-center w-full text-left">
                                    <i class="fas fa-project-diagram mr-3 flex-shrink-0 h-5 w-5"></i>
                                    <span class="flex-1">Projects</span>
                                    <svg class="ml-3 h-5 w-5 transform transition-transform duration-150" :class="{'rotate-90': projectsOpen}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                            <div x-show="projectsOpen" x-transition class="ml-6 space-y-1">
                                <a href="{{ route('admin.projects') }}" 
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.projects') ? 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900 dark:text-indigo-100' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                                    <i class="fas fa-list mr-3 flex-shrink-0 h-4 w-4"></i>
                                    All Projects
                                </a>
                                @if(Route::has('admin.project-categories.index'))
                                <a href="{{ route('admin.project-categories.index') }}" 
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.project-categories.*') ? 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900 dark:text-indigo-100' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                                    <i class="fas fa-tags mr-3 flex-shrink-0 h-4 w-4"></i>
                                    Categories
                                </a>
                                @endif
                                <a href="{{ route('admin.projects.donations') }}" 
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.projects.donations') ? 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900 dark:text-indigo-100' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                                    <i class="fas fa-heart mr-3 flex-shrink-0 h-4 w-4"></i>
                                    Donations Overview
                                </a>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <a href="{{ route('admin.statistics') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.statistics') ? 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900 dark:text-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                            <i class="fas fa-chart-bar mr-3 flex-shrink-0 h-5 w-5"></i>
                            Statistics
                        </a>

                        <!-- Notifications -->
                        <a href="{{ route('admin.notifications.index') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.notifications.*') ? 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900 dark:text-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                            <i class="fas fa-bell mr-3 flex-shrink-0 h-5 w-5"></i>
                            Notifications
                        </a>

                        <!-- Users Management -->
                        <div class="space-y-1" x-data="{ usersOpen: {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'true' : 'false' }} }">
                            <div class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 dark:text-gray-300">
                                <button @click="usersOpen = !usersOpen" class="flex items-center w-full text-left">
                                    <i class="fas fa-cog mr-3 flex-shrink-0 h-5 w-5"></i>
                                    <span class="flex-1">Manage</span>
                                    <svg class="ml-3 h-5 w-5 transform transition-transform duration-150" :class="{'rotate-90': usersOpen}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                            <div x-show="usersOpen" x-transition class="ml-6 space-y-1">
                                @if(Route::has('admin.users.index'))
                                <a href="{{ route('admin.users.index') }}" 
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900 dark:text-indigo-100' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                                    <i class="fas fa-user mr-3 flex-shrink-0 h-4 w-4"></i>
                                    Users
                                </a>
                                @endif
                                @if(Route::has('admin.roles.index'))
                                <a href="{{ route('admin.roles.index') }}" 
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.roles.*') ? 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900 dark:text-indigo-100' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                                    <i class="fas fa-user-shield mr-3 flex-shrink-0 h-4 w-4"></i>
                                    Roles
                                </a>
                                @else
                                <a href="#" 
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                                    <i class="fas fa-user-shield mr-3 flex-shrink-0 h-4 w-4"></i>
                                    Roles
                                </a>
                                @endif
                                @if(Route::has('admin.permissions.index'))
                                <a href="{{ route('admin.permissions.index') }}" 
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.permissions.*') ? 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900 dark:text-indigo-100' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                                    <i class="fas fa-key mr-3 flex-shrink-0 h-4 w-4"></i>
                                    Permissions
                                </a>
                                @else
                                <a href="#" 
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                                    <i class="fas fa-key mr-3 flex-shrink-0 h-4 w-4"></i>
                                    Permissions
                                </a>
                                @endif
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <main class="flex-1 relative focus:outline-none">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
    
    {{-- No need to load Alpine.js separately - it's included with Livewire --}}
    
    <livewire:edit-profile-modal />
</body>
</html>