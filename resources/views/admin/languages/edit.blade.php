@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded-xl shadow">
        <h2 class="text-2xl font-semibold mb-6">{{ __('Edit Language') }}</h2>

        <form action="{{ route('languages.update', $language->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-medium text-gray-700">{{ __('Language Name') }}:</label>
                <input type="text" name="name" value="{{ $language->name }}" required
                       class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block font-medium text-gray-700">{{ __('Slug') }}:</label>
                <input type="text" name="slug" value="{{ $language->slug }}" required
                       class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block font-medium text-gray-700">{{ __('Flag (e.g., 🇹🇷, 🇬🇧)') }}:</label>
                <input type="text" name="flag" value="{{ $language->flag }}"
                       class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-center space-x-3">
                <input type="checkbox" name="is_default" value="1" {{ $language->is_default ? 'checked' : '' }}
                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label class="text-gray-700">{{ __('Is Default Language?') }}</label>
            </div>

            <div class="flex items-center space-x-3">
                <input type="checkbox" name="status" value="1" {{ $language->status ? 'checked' : '' }}
                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label class="text-gray-700">{{ __('Is Active?') }}</label>
            </div>

            <div class="pt-4">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                    {{ __('Update') }}
                </button>
            </div>
        </form>
    </div>
@endsection
