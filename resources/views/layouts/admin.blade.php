<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - ABU Endowment</title>
    <link rel="icon" href="{{ asset('icon/favicon-32x32.png') }}" type="image/png">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        slate: {
                            850: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Flowbite -->
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.1/dist/flowbite.min.css" />
    
    <!-- Trix Editor -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    
    @livewireStyles
    
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .dark ::-webkit-scrollbar-track {
            background: #1e293b;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Sidebar Active State */
        .nav-link.active {
            background-color: #10b981;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.1), 0 2px 4px -1px rgba(16, 185, 129, 0.06);
        }
        .nav-link {
            transition: all 0.2s ease-in-out;
        }
        .nav-link:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.05);
            color: white;
        }

        /* Trix Editor Dark Mode Support */
        .dark trix-toolbar .trix-button {
            background-color: #f3f4f6;
        }
        .dark trix-editor {
            background-color: #1f2937;
            color: #f3f4f6;
            border-color: #374151;
        }
    </style>
</head>

<body class="bg-slate-50 dark:bg-slate-900 font-sans antialiased" x-data="{ sidebarOpen: false }">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Overlay for Mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0f172a] text-slate-300 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 flex flex-col">
            <!-- Logo -->
            <div class="flex items-center justify-center h-20 border-b border-slate-700/50">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 text-white font-bold text-xl tracking-wide">
                    <img src="{{ asset('icon/Header.png') }}" alt="Logo" class="h-8 w-auto">
                    <span>ABU Endowment</span>
                </a>
            </div>

            <!-- Navigation -->
            <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
                
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center px-4 py-3 rounded-xl text-sm font-medium mb-2 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <div class="w-8 flex justify-center"><i class="fas fa-th-large text-lg"></i></div>
                    <span>Dashboard</span>
                </a>

                <!-- Donors -->
                <a href="{{ route('admin.donors.index') }}" class="nav-link flex items-center px-4 py-3 rounded-xl text-sm font-medium mb-2 {{ request()->routeIs('admin.donors.*') ? 'active' : '' }}">
                    <div class="w-8 flex justify-center"><i class="fas fa-users text-lg"></i></div>
                    <span>Donors</span>
                </a>

                <!-- Projects Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('admin.projects*') || request()->routeIs('admin.project-categories*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="nav-link w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium mb-2 {{ request()->routeIs('admin.projects*') || request()->routeIs('admin.project-categories*') ? 'text-white bg-white/5' : '' }}">
                        <div class="flex items-center">
                            <div class="w-8 flex justify-center"><i class="fas fa-project-diagram text-lg"></i></div>
                            <span>Projects</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" x-collapse class="pl-12 pr-2 space-y-1 mb-2">
                        <a href="{{ route('admin.projects') }}" class="block py-2 px-3 rounded-lg text-sm hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.projects') ? 'text-emerald-400' : '' }}">All Projects</a>
                        @if(Route::has('admin.project-categories.index'))
                        <a href="{{ route('admin.project-categories.index') }}" class="block py-2 px-3 rounded-lg text-sm hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.project-categories.*') ? 'text-emerald-400' : '' }}">Categories</a>
                        @endif
                        <a href="{{ route('admin.projects.donations') }}" class="block py-2 px-3 rounded-lg text-sm hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.projects.donations') ? 'text-emerald-400' : '' }}">Donations Overview</a>
                    </div>
                </div>

                <!-- Statistics -->
                <a href="{{ route('admin.statistics') }}" class="nav-link flex items-center px-4 py-3 rounded-xl text-sm font-medium mb-2 {{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
                    <div class="w-8 flex justify-center"><i class="fas fa-chart-pie text-lg"></i></div>
                    <span>Statistics</span>
                </a>

                <!-- Notifications -->
                <a href="{{ route('admin.notifications.index') }}" class="nav-link flex items-center px-4 py-3 rounded-xl text-sm font-medium mb-2 {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                    <div class="w-8 flex justify-center"><i class="fas fa-bell text-lg"></i></div>
                    <span>Notifications</span>
                </a>

                <!-- Manage Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="nav-link w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium mb-2 {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') ? 'text-white bg-white/5' : '' }}">
                        <div class="flex items-center">
                            <div class="w-8 flex justify-center"><i class="fas fa-cog text-lg"></i></div>
                            <span>Manage</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" x-collapse class="pl-12 pr-2 space-y-1 mb-2">
                        @if(Route::has('admin.users.index'))
                        <a href="{{ route('admin.users.index') }}" class="block py-2 px-3 rounded-lg text-sm hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.users.*') ? 'text-emerald-400' : '' }}">Users</a>
                        @endif
                        @if(Route::has('admin.roles.index'))
                        <a href="{{ route('admin.roles.index') }}" class="block py-2 px-3 rounded-lg text-sm hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.roles.*') ? 'text-emerald-400' : '' }}">Roles</a>
                        @endif
                        @if(Route::has('admin.permissions.index'))
                        <a href="{{ route('admin.permissions.index') }}" class="block py-2 px-3 rounded-lg text-sm hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.permissions.*') ? 'text-emerald-400' : '' }}">Permissions</a>
                        @endif
                    </div>
                </div>

            </div>

            <!-- User Profile (Bottom Sidebar) -->
            <div class="p-4 border-t border-slate-700/50">
                <div class="flex items-center gap-3 px-2">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Admin User' }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email ?? 'admin@example.com' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50 dark:bg-slate-900">
            
            <!-- Top Header -->
            <header class="h-20 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between px-8 z-10">
                <!-- Left: Title -->
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Admin Dashboard</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Welcome back! Here's what's happening today.</p>
                </div>

                <!-- Right: Actions -->
                <div class="flex items-center gap-4">
                    
                    <!-- Mobile Menu Toggle -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <!-- Notification Icon -->
                    <button class="relative w-10 h-10 rounded-full bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-600 hover:text-emerald-600 transition-colors">
                        <i class="far fa-bell text-lg"></i>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-slate-700"></span>
                    </button>

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center gap-3 pl-4 border-l border-slate-200 dark:border-slate-700">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ Auth::user()->name ?? 'Admin' }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Administrator</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 border-2 border-white dark:border-slate-700 shadow-sm">
                                <span class="font-bold">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-100 dark:border-slate-700 py-1 z-50">
                            
                            <a href="#" @click.prevent="$dispatch('open-profile-modal'); open = false" class="flex items-center px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-emerald-600">
                                <i class="fas fa-user-cog w-5 mr-2"></i> Profile Settings
                            </a>
                            
                            <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>
                            
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                                    <i class="fas fa-sign-out-alt w-5 mr-2"></i> Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

<<<<<<< HEAD
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
                                <a href="{{ route('admin.reports.index') }}" 
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900 dark:text-indigo-100' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                                    <i class="fas fa-file-alt mr-3 flex-shrink-0 h-4 w-4"></i>
                                    Report
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
=======
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-8">
                @yield('content')
                {{ $slot ?? '' }}
>>>>>>> 33e9575b334311455b926704b6fd78629e658ba3
            </main>
        </div>
    </div>

    @livewireScripts
    <livewire:edit-profile-modal />
</body>
</html>