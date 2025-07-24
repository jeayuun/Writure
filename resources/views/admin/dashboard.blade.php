@extends('layouts.admin')

@section('content')
    <div class="space-y-8">
        {{-- Updated Welcome Header with Profile Photo --}}
        <div class="flex items-center space-x-6">
            <img class="h-20 w-20 rounded-full object-cover" 
                 src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://placehold.co/256x256/EFEFEF/333333?text=' . substr(Auth::user()->name, 0, 1) }}" 
                 alt="{{ Auth::user()->name }}">
            <div>
                <h1 class="text-3xl font-serif font-semibold text-gray-800">{{ __('Welcome back,') }} {{ Auth::user()->name }}!</h1>
                <p class="text-gray-500 mt-1">Here's a snapshot of Writure today.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            {{-- Total Users --}}
            <div class="bg-white p-6 rounded-lg shadow-sm flex items-center">
                <div class="bg-purple-100 text-purple-600 rounded-full p-3 flex-shrink-0">
                    <i class="fas fa-users fa-fw fa-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">{{ __('Total Users') }}</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</p>
                </div>
            </div>

            {{-- Total Posts --}}
            <div class="bg-white p-6 rounded-lg shadow-sm flex items-center">
                <div class="bg-blue-100 text-blue-600 rounded-full p-3 flex-shrink-0">
                    <i class="fas fa-pencil-alt fa-fw fa-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">{{ __('Total Posts') }}</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalPosts }}</p>
                </div>
            </div>

            {{-- Total Categories --}}
            <div class="bg-white p-6 rounded-lg shadow-sm flex items-center">
                <div class="bg-yellow-100 text-yellow-600 rounded-full p-3 flex-shrink-0">
                    <i class="fas fa-folder fa-fw fa-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">{{ __('Total Categories') }}</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalCategories }}</p>
                </div>
            </div>

            {{-- Total Tags --}}
            <div class="bg-white p-6 rounded-lg shadow-sm flex items-center">
                <div class="bg-pink-100 text-pink-600 rounded-full p-3 flex-shrink-0">
                    <i class="fas fa-tags fa-fw fa-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">{{ __('Total Tags') }}</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalTags }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 ">Recent Posts</h2>
                <div>
                    @forelse ($recentPosts as $post)
                        <div class="flex items-center justify-between p-4 rounded-md hover:bg-gray-50 transition" style="border-bottom: 1px solid #e5e7eb;">
                            <div>
                                <a href="{{ route('posts.edit', $post->id) }}" class="font-medium text-gray-700 hover:text-black">{{ $post->translations->first()->title ?? 'Untitled Post' }}</a>
                                <p class="text-sm text-gray-500">Last updated: {{ $post->updated_at->format('M d, Y') }}</p>
                            </div>
                            <a href="{{ route('posts.edit', $post->id) }}" style="display: inline-block; padding: 8px 24px; border: 1px solid #d1d5db; border-radius: 9999px; color: #374151; background-color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 500;">Edit</a>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No recent posts to show.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('posts.create') }}" class="w-full text-left block bg-black text-white px-5 py-3 rounded-md font-normal hover:bg-gray-800 transition">
                        <i class="fas fa-plus-circle mr-2"></i> Create New Post
                    </a>
                     <a href="{{ route('categories.create') }}" class="w-full text-left block bg-gray-200 text-gray-800 px-5 py-3 rounded-md font-normal hover:bg-gray-300 transition">
                        <i class="fas fa-folder-plus mr-2"></i> Add Category
                    </a>
                     <a href="{{ route('tags.create') }}" class="w-full text-left block bg-gray-200 text-gray-800 px-5 py-3 rounded-md font-normal hover:bg-gray-300 transition">
                       <i class="fas fa-tag mr-2"></i> Add Tag
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection