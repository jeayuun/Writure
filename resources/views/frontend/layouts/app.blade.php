<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Writure'))</title>

    @yield('meta_tags')

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .font-serif {
            font-family: 'Merriweather', serif;
        }
    </style>
    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen bg-white">
        
        {{-- Navigation Menu --}}
        <nav x-data="{ open: false }" class="bg-white">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8" style="border-bottom: 1px solid #eaeaea;">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('frontend.home', ['lang' => $lang ?? 'en']) }}" class="text-3xl font-serif font-normal">
                               Writure
                            </a>
                        </div>
                    </div>

                    @auth
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <span class="mr-3 text-gray-600">{{ Auth::user()->username }}</span>
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                       <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://placehold.co/256x256/EFEFEF/333333?text=' . substr(Auth::user()->name, 0, 1) }}" alt="{{ Auth::user()->name }}">
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="px-4 py-2 border-b">
                                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                                    </div>
                                    <x-dropdown-link :href="route('dashboard')">
                                        {{ __('My Dashboard') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile Settings') }}
                                    </x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                         onclick="event.preventDefault();
                                                                     this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @else
                        <div class="hidden sm:flex items-center space-x-8">
                             <a href="{{ route('login') }}" class="text-gray-700 hover:text-black transition">Sign In</a>
                             <a href="{{ route('register') }}" class="bg-black text-white px-6 py-3 rounded-full hover:bg-gray-800 transition">Start Writing</a>
                        </div>
                    @endauth

                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                @auth
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <x-responsive-nav-link :href="route('dashboard')">
                                {{ __('My Dashboard') }}
                            </x-responsive-nav-link>
                             <x-responsive-nav-link :href="route('profile.edit')">
                                {{ __('Profile Settings') }}
                            </x-responsive-nav-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-responsive-nav-link :href="route('logout')"
                                                     onclick="event.preventDefault();
                                                                 this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-responsive-nav-link>
                            </form>
                        </div>
                    </div>
                @else
                     <div class="pt-2 pb-3 space-y-1">
                        <x-responsive-nav-link :href="route('login')">
                            {{ __('Sign In') }}
                        </x-responsive-nav-link>
                         <x-responsive-nav-link :href="route('register')">
                            {{ __('Start Writing') }}
                        </x-responsive-nav-link>
                    </div>
                @endauth
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>

    {{-- Main Site Footer --}}
    <footer class="bg-black text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                <div class="text-4xl font-serif font-normal">Writure</div>
                <nav class="flex space-x-8">
                    <a href="#" class="hover:text-gray-300 transition">About</a>
                    <a href="#" class="hover:text-gray-300 transition">Contact Us</a>
                </nav>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>