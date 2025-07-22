@extends('layouts.admin')

@section('content')
    <div class="bg-white p-8 rounded-lg shadow-sm">
        <h1 class="text-2xl font-normal text-gray-800 mb-6">{{ __('Edit Category') }}</h1>

        {{-- The form action is corrected to pass the $category model directly. --}}
        {{-- Laravel will automatically resolve the correct ID for the 'category' parameter. --}}
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

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
                            @php
                                // Find the translation for the current language, if it exists.
                                $translation = $category->translations->where('language_slug', $language->slug)->first();
                            @endphp
                            <div x-show="activeTab === '{{ $language->slug }}'" x-cloak>
                                <div class="mb-4">
                                    <label for="name_{{ $language->slug }}" class="block text-sm font-medium text-gray-700">
                                        {{ __('Name') }} ({{ strtoupper($language->slug) }})
                                    </label>
                                    <input type="text" name="translations[{{ $language->slug }}][name]" id="name_{{ $language->slug }}"
                                           value="{{ old('translations.'.$language->slug.'.name', $translation->name ?? '') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm">
                                    <input type="hidden" name="translations[{{ $language->slug }}][language_slug]" value="{{ $language->slug }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end items-center">
                <a href="{{ route('categories.index') }}" class="text-gray-600 px-4 py-2 rounded-md mr-2 hover:bg-gray-100 transition">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="bg-black text-white px-5 py-2 rounded-md font-normal hover:bg-gray-800 transition">
                    {{ __('Update Category') }}
                </button>
            </div>
        </form>
    </div>
@endsection
