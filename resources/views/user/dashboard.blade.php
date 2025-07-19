@extends('frontend.layouts.app', ['lang' => app()->getLocale()])

@section('title', __('My Dashboard'))

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Welcome Header -->
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8 mb-8">
            <h1 class="text-3xl font-bold text-gray-800">{{ __('Welcome back') }}, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-600 mt-2">{{ __("Here you can manage your posts and view content from the community.") }}</p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Create Post -->
            <a href="#" class="group block bg-white p-6 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transform transition-all duration-300">
                <div class="flex items-center">
                    <div class="bg-green-100 text-green-600 p-3 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-800">{{ __('Create New Post') }}</h3>
                        <p class="text-gray-500 text-sm">{{ __('Share your thoughts with the world.') }}</p>
                    </div>
                </div>
            </a>
            <!-- My Posts -->
            <a href="#" class="group block bg-white p-6 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transform transition-all duration-300">
                <div class="flex items-center">
                    <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-800">{{ __('My Posts') }}</h3>
                        <p class="text-gray-500 text-sm">{{ __('View and manage your contributions.') }}</p>
                    </div>
                </div>
            </a>
            <!-- All Posts -->
            <a href="{{ route('frontend.home', ['lang' => app()->getLocale()]) }}" class="group block bg-white p-6 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transform transition-all duration-300">
                <div class="flex items-center">
                    <div class="bg-purple-100 text-purple-600 p-3 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-800">{{ __('View All Posts') }}</h3>
                        <p class="text-gray-500 text-sm">{{ __('Explore posts from all users.') }}</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Recent Activity (Placeholder) -->
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ __('Recent Activity') }}</h2>
            <div class="text-center text-gray-500 py-8">
                <p>{{ __("Recent comments and likes will be shown here soon!") }}</p>
            </div>
        </div>

    </div>
</div>
@endsection
