@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-xl shadow">
        <h2 class="text-2xl font-semibold mb-6">{{ __('Add New Category') }}</h2>

        <form action="{{ route('categories.store') }}" method="POST" class="space-y-6">
            @csrf

            @foreach($languages as $language)
                <div class="border border-gray-300 rounded p-4">
                    <h3 class="text-lg font-semibold mb-4">{{ $language->name }} ({{ $language->slug }})</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('Category Name') }}:</label>
                        <input type="text"
                               name="translations[{{ $language->slug }}][name]"
                               required
                               class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('Slug') }}:</label>
                        <input type="text"
                               name="translations[{{ $language->slug }}][slug]"
                               required
                               class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('SEO Title') }}:</label>
                        <input type="text"
                               name="translations[{{ $language->slug }}][seo_title]"
                               class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('SEO Description') }}:</label>
                        <textarea name="translations[{{ $language->slug }}][seo_description]"
                                  rows="3"
                                  class="w-full mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('SEO Keywords') }}:</label>
                        <input type="text"
                               name="translations[{{ $language->slug }}][seo_keywords]"
                               class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            @endforeach

            <div>
                <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition">
                    {{ __('Save') }}
                </button>
            </div>
        </form>
    </div>
@endsection
