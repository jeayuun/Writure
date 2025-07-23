<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __('Admin Panel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" xintegrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Roboto', sans-serif;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }
        
        .sidebar-link.active {
            background-color: #1F2937; 
            color: #ffffff;
            font-weight: 500;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-gray-100 text-gray-800">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        <aside class="bg-black text-gray-400 w-72 flex-shrink-0 hidden md:block">
            <div class="p-8">
                <a href="{{ route('dashboard') }}" class="text-3xl font-serif font-bold text-white">{{ __('Writure') }}</a>
                 <p class="text-sm text-gray-500 mt-1">Admin Panel</p>
            </div>

            @php
                $routeName = Route::currentRouteName();
                $routePrefix = explode('.', $routeName)[0];
            @endphp

            <nav class="mt-4 space-y-2">
                <a href="{{ route('dashboard') }}"
                   class="sidebar-link flex items-center px-8 py-3 transition hover:bg-gray-900 hover:text-white {{ $routePrefix == 'dashboard' ? 'active' : '' }}">
                   <i class="fas fa-tachometer-alt w-6 text-center"></i>
                   <span class="ml-4">{{ __('Dashboard') }}</span>
                </a>

                <a href="{{ route('users.index') }}"
                   class="sidebar-link flex items-center px-8 py-3 transition hover:bg-gray-900 hover:text-white {{ $routePrefix == 'users' ? 'active' : '' }}">
                   <i class="fas fa-language w-6 text-center"></i>
                   <span class="ml-4">{{ __('Users') }}</span>
                </a>

                <!-- Blog Management Dropdown -->
                <div x-data="{ open: {{ in_array($routePrefix, ['categories', 'tags', 'posts']) ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="sidebar-link w-full flex items-center justify-between px-8 py-3 transition hover:bg-gray-900 hover:text-white {{ in_array($routePrefix, ['categories', 'tags', 'posts']) ? 'active' : '' }}">
                        <span class="flex items-center">
                            <i class="fas fa-pencil-alt w-6 text-center"></i>
                            <span class="ml-4">{{ __('Blog Management') }}</span>
                        </span>
                        <i class="fas text-xs" :class="{'fa-chevron-down': !open, 'fa-chevron-up': open}"></i>
                    </button>
                    <div x-show="open" x-cloak class="bg-gray-900 pl-12 py-2">
                        <a href="{{ route('categories.index') }}" class="block px-4 py-2 text-sm rounded transition hover:text-white {{ $routePrefix == 'categories' ? 'text-white' : '' }}">
                            {{ __('Categories') }}
                        </a>
                        <a href="{{ route('tags.index') }}" class="block px-4 py-2 text-sm rounded transition hover:text-white {{ $routePrefix == 'tags' ? 'text-white' : '' }}">
                            {{ __('Tags') }}
                        </a>
                        <a href="{{ route('posts.index') }}" class="block px-4 py-2 text-sm rounded transition hover:text-white {{ $routePrefix == 'posts' ? 'text-white' : '' }}">
                            {{ __('Posts') }}
                        </a>
                    </div>
                </div>

                <a href="{{ route('languages.index') }}"
                   class="sidebar-link flex items-center px-8 py-3 transition hover:bg-gray-900 hover:text-white {{ $routePrefix == 'languages' ? 'active' : '' }}">
                   <i class="fas fa-language w-6 text-center"></i>
                   <span class="ml-4">{{ __('Languages') }}</span>
                </a>

                <hr class="border-gray-800 mx-8">

                <a href="{{ route('cacheclear') }}"
                   class="sidebar-link flex items-center px-8 py-3 transition hover:bg-gray-900 hover:text-white {{ $routePrefix == 'cacheclear' ? 'active' : '' }}">
                   <i class="fas fa-broom w-6 text-center"></i>
                   <span class="ml-4">{{ __('Clear Cache') }}</span>
                </a>
            </nav>
        </aside>

        {{-- Content Area --}}
        <div class="flex-1 flex flex-col overflow-y-auto bg-gray-50">
            {{-- Header --}}
            <header class="bg-white border-b border-gray-100 flex items-center justify-end px-8 py-4">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center text-gray-600 focus:outline-none">
                        <span class="mr-3">{{ Auth::user()->username }}</span>
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://placehold.co/32x32/EFEFEF/333333?text=' . substr(Auth::user()->name, 0, 1) }}" alt="{{ Auth::user()->name }}">
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute right-0 mt-2 w-48 bg-white text-gray-700 rounded-md shadow-lg z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">
                            {{ __('Profile') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                {{ __('Logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="p-8">
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                 @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>

</html>
