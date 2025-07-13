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
    
    @livewireStyles
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        
                        <div class="flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-indigo-600 dark:text-indigo-400 flex justify-around">
                            <img src="{{ asset('icon/Header.png') }}" alt="ABU Endowment" class="h-12 w-auto mr-3 align-baseline">
                                ABU Endowment Admin
                            </a>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative group">
                        <button class="focus:outline-none" @click="$dispatch('open-profile-modal')">
                            <i class="fas fa-user-circle text-2xl text-indigo-600 dark:text-indigo-400"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                            <a href="#" @click.prevent="$dispatch('open-profile-modal')" class="block px-4 py-2 text-gray-700 hover:bg-indigo-100">Profile</a>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-indigo-100">Sign Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium px-3 py-2 rounded transition">
            <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
        </a>
        @yield('content')
    </main>

    @livewireScripts
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/flowbite@1.5.1/dist/flowbite.js"></script>
    <livewire:edit-profile-modal />
</body>
</html> 