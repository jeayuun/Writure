<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-g">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Explore Blogs | Write.</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <header class="bg-gray-800 border-b border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('frontend.home', ['lang' => $lang]) }}">
                            <h1 class="text-white font-bold text-xl" style="font-family: Merriweather,serif; font-size: 2.25rem; line-height: 2.5rem;">Write.</h1>
                        </a>
                    </div>

                    <!-- Language Switcher & Auth Links -->
                    <div class="flex items-center space-x-4">
                        @if ($currentLanguage)
                            <!-- Language.Switcher -->
                            <div class="relative group" x-data="{ open: false }" x-init="open = false">
                                <button @click="open = !open"
                                    class="flex items-center space-x-1 px-4 py-2 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                                    <img src="https://flagcdn.com/{{ $currentLanguage->flag }}.svg"
                                        class="w-6 h-4 rounded-sm shadow" alt="{{ $currentLanguage->name }}">
                                    <span
                                        class="font-medium">{{ strtoupper($currentLanguage->slug) }}</span>
                                </button>
                                <div x-show="open" @click.away="open = false"
                                    class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-1 z-50">
                                    @foreach ($languages as $language)
                                        <a href="{{ route('language.switch', ['lang' => $language->slug, 'encodedPath' => base64_encode(request()->path())]) }}"
                                            class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <img src="https://flagcdn.com/{{ $language->flag }}.svg"
                                                class="w-6 h-4 rounded-sm mr-3" alt="{{ $language->name }}">
                                            <span>{{ $language->name }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif


                        <!-- Authentication Links -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            @auth
                                <!-- Settings Dropdown -->
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-400 bg-gray-800 hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                            <div>{{ Auth::user()->name }}</div>

                                            <div class="ms-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('profile.edit')">
                                            {{ __('Profile') }}
                                        </x-dropdown-link>

                                        <!-- Authentication -->
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
                            @else
                                <a href="{{ route('login') }}"
                                    class="font-semibold text-gray-400 hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">{{ __('Sign In') }}</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="ms-4 font-semibold text-gray-400 hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">{{ __('Start Writing') }}</a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
</body>

</html>
