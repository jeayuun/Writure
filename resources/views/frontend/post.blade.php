@extends('frontend.layouts.app')

@section('title', $translation->title)

@section('meta_tags')
    <meta name="description" content="{{ $translation->short_description ?? Str::limit(strip_tags($translation->content), 150) }}">
@endsection

@push('styles')
<style>
    .prose h1, .prose h2, .prose h3 {
        font-family: 'Merriweather', serif;
    }
    .prose p, .prose li {
        font-family: 'Roboto', sans-serif;
        font-size: 1.125rem;
        line-height: 1.75;
    }
</style>
@endpush

@section('content')
    <div class="bg-white py-12">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            
            <article class="max-w-3xl mx-auto">
                
                {{-- Title and Action Dropdown --}}
                <div class="flex justify-between items-start gap-4">
                    <h1 class="text-4xl md:text-5xl font-serif font-bold text-gray-900 leading-tight">
                        {{ $translation->title }}
                    </h1>

                    {{-- This dropdown will ONLY show if the logged-in user is the author --}}
                    @auth
                        @if(Auth::user()->id === $translation->post->user_id)
                            <div class="relative flex-shrink-0">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="p-2 rounded-full text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>
                                    </x-slot>

                                    {{-- Dropdown Content --}}
                                    <x-slot name="content" >
                                        <x-dropdown-link :href="route('user.posts.edit', $translation->post)">
                                            {{ __('Edit') }}
                                        </x-dropdown-link>

                                        <div class="border-t border-gray-100"></div>

                                        <form method="POST" action="{{ route('user.posts.destroy', $translation->post) }}" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-red-700 hover:bg-red-50 focus:outline-none focus:bg-red-50 transition duration-150 ease-in-out">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endif
                    @endauth
                </div>

                {{-- Author Info --}}
                <div class="mt-6 flex items-center space-x-4">
                    <img class="h-12 w-12 rounded-full object-cover" 
                         src="{{ $translation->post->user->profile_photo_path ? asset('storage/' . $translation->post->user->profile_photo_path) : 'https://placehold.co/256x256/EFEFEF/333333?text=' . substr($translation->post->user->name, 0, 1) }}" 
                         alt="{{ $translation->post->user->name }}">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $translation->post->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $translation->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                {{-- Cover Image --}}
                @if($translation->post->cover_image)
                    <img src="{{ asset($translation->post->cover_image) }}" alt="{{ $translation->title }}" class="w-full mt-8 rounded-lg">
                @endif
                
                {{-- Post Content --}}
                <div class="prose max-w-none mt-8">
                    {!! $translation->content !!}
                </div>

                {{-- Author Box & Sharing --}}
                <div class="mt-16 grid grid-cols-1 md:grid-cols-2 gap-8 border-t pt-8">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-xs uppercase font-semibold text-gray-500 mb-3">About The Author</h3>
                        <a href="{{ route('frontend.author.show', $translation->post->user->username) }}" class="flex items-center space-x-3 group">
                            <img class="h-12 w-12 rounded-full object-cover group-hover:opacity-90 transition" 
                                src="{{ $translation->post->user->profile_photo_path ? asset('storage/' . $translation->post->user->profile_photo_path) : 'https://placehold.co/256x256/EFEFEF/333333?text=' . substr($translation->post->user->name, 0, 1) }}" 
                                alt="{{ $translation->post->user->name }}">
                            <p class="font-semibold text-gray-800 group-hover:underline">{{ $translation->post->user->name }}</p>
                        </a>
                    </div>
                    <div class="border rounded-lg p-4">
                        <h3 class="text-xs uppercase font-semibold text-gray-500 mb-3">Share This Article</h3>
                        <div class="flex items-center space-x-3">
                            <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-700 transition"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-400 text-white hover:bg-blue-500 transition"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-700 text-white hover:bg-blue-800 transition"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-green-500 text-white hover:bg-green-600 transition"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
            </article>

            {{-- More On Writure --}}
            @if($morePosts->count() > 0)
                <div class="mt-24 border-t pt-16">
                    <h2 class="text-2xl font-serif font-bold text-gray-800 mb-8">More On Writure</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @foreach($morePosts as $post)
                            <div class="flex flex-col group">
                                <a href="{{ route('frontend.post.show', [$lang, $post->translations->first()->slug]) }}">
                                    <img src="{{ asset($post->cover_image) }}" alt="{{ $post->translations->first()->title }}" class="w-full h-48 object-cover rounded-lg transform group-hover:scale-105 transition-transform duration-300">
                                </a>
                                <div class="mt-4">
                                    <h3 class="font-semibold text-lg mb-2 text-gray-800">{{ $post->translations->first()->title }}</h3>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <img class="h-6 w-6 rounded-full object-cover mr-2" 
                                             src="{{ $post->user->profile_photo_path ? asset('storage/' . $post->user->profile_photo_path) : 'https://placehold.co/256x256/EFEFEF/333333?text=' . substr($post->user->name, 0, 1) }}" 
                                             alt="{{ $post->user->name }}">
                                        <span>{{ $post->user->name }} &middot; {{ $post->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection