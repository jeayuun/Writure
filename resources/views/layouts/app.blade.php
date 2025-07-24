<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Writure') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
             body {
                font-family: 'Roboto', sans-serif;
            }
            .font-serif {
                font-family: 'Playfair Display', serif;
            }
        </style>
        @stack('styles') </head>
    <body class="font-sans antialiased bg-white">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <main class="flex-grow">
                {{ $slot }}
            </main>

            @if (!request()->routeIs('user.posts.create', 'user.posts.edit'))
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
            @endif
        </div>
        @stack('scripts') </body>
</html>