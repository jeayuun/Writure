@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded-xl shadow">
        <h2 class="text-2xl font-semibold mb-6">{{ __('Add New Language') }}</h2>

        <form action="{{ route('languages.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block font-medium text-gray-700">{{ __('Language Name') }}:</label>
                <input type="text" name="name" required
                       class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block font-medium text-gray-700">{{ __('Slug (e.g., en, tr)') }}:</label>
                <input type="text" name="slug" required
                       class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block font-medium text-gray-700">{{ __('Flag (emoji or file name)') }}:</label>
                <input type="text" name="flag"
                       class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-center space-x-3">
                <input type="checkbox" name="is_default" value="1"
                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label class="text-gray-700">{{ __('Is Default Language?') }}</label>
            </div>

            <div class="flex items-center space-x-3">
                <input type="checkbox" name="status" value="1" checked
                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label class="text-gray-700">{{ __('Is Active?') }}</label>
            </div>

            <div class="pt-4">
                <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition">
                    {{ __('Save') }}
                </button>
            </div>
        </form>
    </div>
@endsection
