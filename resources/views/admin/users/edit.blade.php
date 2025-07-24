@extends('layouts.admin')

@section('content')
    <div class="bg-white p-8 rounded-lg shadow-sm">
        <h1 class="text-2xl font-semibold font-serif text-gray-800 mb-6">{{ __('Edit User') }}</h1>

        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                 <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Full Name') }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm" required>
                </div>
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">{{ __('Username') }}</label>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm" required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email Address') }}</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm" required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">{{ __('New Password (leave blank to keep current)') }}</label>
                    <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('Confirm New Password') }}</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm">
                </div>
                 <div>
                    <label for="profile_photo_path" class="block text-sm font-medium text-gray-700">{{ __('Profile Photo') }}</label>
                    <input type="file" name="profile_photo_path" id="profile_photo_path" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                    @if ($user->profile_photo_path)
                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Current profile photo" class="mt-2 h-20 w-20 rounded-full object-cover">
                    @endif
                </div>
                <div class="flex items-center">
                    {{-- Removed the hidden input --}}
                    <input id="is_admin" name="is_admin" type="checkbox" value="1" class="h-4 w-4 text-black rounded border-gray-300 focus:ring-black" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                    <label for="is_admin" class="ml-2 block text-sm text-gray-900">{{ __('Assign as Administrator') }}</label>
                </div>
            </div>
            <div class="flex justify-end items-center mt-8 border-t border-gray-200 pt-6">
                <a href="{{ route('users.index') }}" class="text-gray-600 px-4 py-2 rounded-md mr-2 hover:bg-gray-100 transition">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="bg-black text-white px-5 py-2 rounded-md font-normal hover:bg-gray-800 transition">
                    {{ __('Update User') }}
                </button>
            </div>
        </form>
    </div>
@endsection