@extends('layouts.admin')

@section('content')
    <div class="bg-white p-8 rounded-lg shadow-sm">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">{{ __('Add New Category') }}</h1>

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div class="mb-8">
                {{-- Language Tabs --}}
                <div x-data="{ activeTab: '{{ $languages->first()->slug }}' }">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            @foreach ($languages as $language)
                                <a href="#" @click.prevent="activeTab = '{{ $language->slug }}'"
                                   :class="{
                                        'border-black text-gray-900': activeTab === '{{ $language->slug }}',
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== '{{ $language->slug }}'
                                   }"
                                   class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 ease-in-out">
                                    {{ $language->name }}
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    {{-- Tab Content --}}
                    <div class="mt-6">
                        @foreach ($languages as $language)
                            <div x-show="activeTab === '{{ $language->slug }}'" x-cloak class="space-y-6">
                                {{-- Hidden language slug input --}}
                                <input type="hidden" name="translations[{{ $language->slug }}][language_slug]" value="{{ $language->slug }}">

                                {{-- Category Name --}}
                                <div>
                                    <label for="name_{{ $language->slug }}" class="block text-sm font-medium text-gray-700">
                                        {{ __('Category Name') }} ({{ strtoupper($language->slug) }})
                                    </label>
                                    <input type="text" name="translations[{{ $language->slug }}][name]" id="name_{{ $language->slug }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm"
                                           placeholder="Enter category name" required>
                                </div>

                                {{-- SEO Fields --}}
                                <div class="space-y-6 pt-6 border-t border-gray-200">
                                     <div>
                                        <label for="seo_title_{{ $language->slug }}" class="block text-sm font-medium text-gray-700">{{ __('SEO Title') }}</label>
                                        <input type="text" name="translations[{{ $language->slug }}][seo_title]" id="seo_title_{{ $language->slug }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm"
                                               placeholder="Enter SEO title">
                                    </div>

                                    <div>
                                        <label for="seo_description_{{ $language->slug }}" class="block text-sm font-medium text-gray-700">{{ __('SEO Description') }}</label>
                                        <textarea name="translations[{{ $language->slug }}][seo_description]" id="seo_description_{{ $language->slug }}" rows="3"
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm"
                                                  placeholder="Enter SEO description"></textarea>
                                    </div>
                                     <div>
                                        <label for="seo_keywords_{{ $language->slug }}" class="block text-sm font-medium text-gray-700">{{ __('SEO Keywords') }}</label>
                                        <input type="text" name="translations[{{ $language->slug }}][seo_keywords]" id="seo_keywords_{{ $language->slug }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm"
                                               placeholder="keyword1, keyword2, keyword3">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end items-center border-t border-gray-200 pt-6">
                <a href="{{ route('categories.index') }}" class="text-gray-600 px-4 py-2 rounded-md mr-2 hover:bg-gray-100 transition">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="bg-black text-white px-5 py-2 rounded-md font-normal hover:bg-gray-800 transition">
                    {{ __('Save Category') }}
                </button>
            </div>
        </form>
    </div>
@endsection
