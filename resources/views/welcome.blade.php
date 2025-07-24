<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Writure') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .font-serif {
            font-family: 'Merriweather', serif;
        }
    </style>
</head>
<body class="bg-white text-gray-800">
    <div class="container mx-auto px-6">
        <header class="flex justify-between items-center py-5" style="border-bottom: 1px solid #eaeaea;"> 
            <div class="text-4xl font-serif">Writure</div>
            <nav class="flex items-center space-x-8">
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-black transition">Sign In</a>
                <a href="{{ route('register') }}" class="bg-black text-white px-6 py-3 rounded-full hover:bg-gray-800 transition">Start Writing</a>
            </nav>
        </header>

        <main>
            <section class="py-5">
                <div class="grid md:grid-cols-2 gap-16 items-center">
                    <div class="space-y-8">
                        <h1 class="text-7xl font-serif font-normal leading-tight" style="line-height: 1;">Share Your Thoughts, Tell Your Story. Be a Writure.</h1>
                        <p class="text-gray-500 text-lg font-light">Join a community of passionate writers and readers. Share your thoughts, create insightful blogs, and connect with people from around the world. Writure gives you the tools to express yourself, learn from others, and make your voice heard.</p>
                        <a href="{{ route('register') }}" class="bg-black text-white px-6 py-2 rounded-full inline-block hover:bg-gray-800 transition text-lg">Start Writing</a>
                    </div>
                    <div>
                        <img src="{{ asset('images/hero-image.png') }}" alt="A person writing on a laptop" class="w-full h-auto">
                    </div>
                </div>
            </section>

            <section class="pt-12 pb-24">
                <h2 class="text-2xl font-serif font-normal mb-10">Featured Blogs</h2>
                <div class="grid md:grid-cols-3 gap-10">
                    @forelse($posts as $post)
                        {{-- Added `flex flex-col` to make the card a flex container --}}
                        <div class="bg-white rounded-lg overflow-hidden group flex flex-col">
                            @if($post->cover_image)
                            <a href="{{ route('frontend.post.show', [app()->getLocale(), $post->translations->first()->slug]) }}">
                                <img src="{{ asset($post->cover_image) }}" alt="{{ $post->translations->first()->title ?? '' }}" class="w-full h-56 object-cover transform group-hover:scale-105 transition-transform duration-300">
                            </a>
                            @endif
                            {{-- Added `flex flex-col flex-grow` to make this section fill available space --}}
                            <div class="p-4 flex flex-col flex-grow">
                                {{-- Set a fixed height and line-clamp for the title --}}
                                <h3 class="font-normal text-xl mb-3 h-14 line-clamp-2">{{ $post->translations->first()->title ?? '' }}</h3>
                                
                                {{-- Short Description --}}
                                <p class="text-gray-500 text-base font-light line-clamp-3 mb-4">
                                    {{ $post->translations->first()->short_description ?? '' }}
                                </p>

                                {{-- Added `mt-auto` to push this to the bottom of the card --}}
                                <div class="mt-auto pt-4 border-t border-gray-100 flex items-center text-sm text-gray-500">
                                     @if($post->user)
                                        <img class="h-8 w-8 rounded-full object-cover mr-3" 
                                             src="{{ $post->user->profile_photo_path ? asset('storage/' . $post->user->profile_photo_path) : 'https://placehold.co/256x256/EFEFEF/333333?text=' . substr($post->user->name, 0, 1) }}" 
                                             alt="{{ $post->user->name }}">
                                        <span>{{ $post->user->name }} &middot; {{ $post->created_at->format('M d, Y') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 md:col-span-3">No recommended blogs found at the moment.</p>
                    @endforelse
                </div>
            </section>
        </main>
    </div>

    <footer class="bg-black text-white">
        <div class="container mx-auto px-6 py-10 flex justify-between items-center">
            <div class="text-4xl font-serif font-normal">Writure</div>
            <nav class="flex space-x-8">
                <a href="#" class="hover:text-gray-300 transition">About</a>
                <a href="#" class="hover:text-gray-300 transition">Contact Us</a>
            </nav>
        </div>
    </footer>
</body>
</html>