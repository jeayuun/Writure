@extends('frontend.layouts.app')

@section('title', 'Posts by ' . $author->name)

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Author Header --}}
        <div class="flex items-center mb-16 px-4 sm:px-0">
            <img class="h-24 w-24 rounded-full object-cover" src="{{ $author->profile_photo_path ? asset('storage/' . $author->profile_photo_path) : 'https://placehold.co/256x256/EFEFEF/333333?text=' . substr($author->name, 0, 1) }}" alt="{{ $author->name }}">
            <div class="ml-6">
                <h1 class="text-4xl font-serif text-gray-800">{{ $author->name }}</h1>
                <p class="text-gray-500 mt-1">{{ $author->email }}</p>
            </div>
        </div>

        @if($posts->isEmpty())
            <div class="text-center py-24">
                <p class="text-gray-500 mb-4">{{ $author->name }} has not published any posts yet.</p>
            </div>
        @else
            <h2 class="text-2xl font-serif font-normal text-gray-800 mb-8 px-4 sm:px-0">All posts by {{ $author->name }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <div class="bg-white rounded-lg overflow-hidden flex flex-col group shadow-sm hover:shadow-lg transition-shadow duration-300">
                        @if($post->cover_image)
                            <a href="{{ route('frontend.post.show', [app()->getLocale(), $post->translations->first()->slug]) }}">
                                <img src="{{ asset($post->cover_image) }}" alt="{{ $post->translations->first()->title ?? '' }}" class="w-full h-56 object-cover">
                            </a>
                        @endif
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="font-semibold text-xl mb-2 text-gray-800">{{ $post->translations->first()->title ?? '' }}</h3>
                            <p class="text-gray-600 text-sm flex-grow">{{ $post->translations->first()->short_description ?? '' }}</p>
                            <p class="text-xs text-gray-400 mt-4">{{ $post->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
             <div class="mt-12">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection