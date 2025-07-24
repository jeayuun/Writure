@extends('layouts.admin')

@section('content')
    <div class="bg-white p-8 rounded-lg shadow-sm">
        <h1 class="text-2xl font-semibold font-serif text-gray-800 mb-6">{{ __('Edit Tag') }}</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Oops! Something went wrong.</strong>
                <ul class="mt-3 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tags.update', $tag) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6 border-b border-gray-200">
                <nav class="flex space-x-4" aria-label="Tabs">
                    @foreach($languages as $index => $language)
                        <button type="button"
                                class="tab-button {{ $index === 0 ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                data-target="tab-{{ $language->slug }}">
                            {{ $language->name }}
                        </button>
                    @endforeach
                </nav>
            </div>

            <div>
                @foreach($languages as $index => $language)
                    @php
                        $translation = $tag->translations->where('language_slug', $language->slug)->first();
                    @endphp
                    <div id="tab-{{ $language->slug }}" class="tab-content {{ $index === 0 ? 'block' : 'hidden' }} space-y-6">
                        <div>
                            <label for="name-{{ $language->slug }}" class="block text-sm font-medium text-gray-700">
                                {{ __('Tag Name') }} ({{ strtoupper($language->slug) }})
                                @if($language->is_default) <span class="text-red-500">*</span> @endif
                            </label>
                            <input type="text" name="name[{{ $language->slug }}]" id="name-{{ $language->slug }}" value="{{ old('name.' . $language->slug, $translation->name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm">
                        </div>
                         <div>
                            <label for="seo_title-{{ $language->slug }}" class="block text-sm font-medium text-gray-700">{{ __('SEO Title') }} ({{ strtoupper($language->slug) }})</label>
                            <input type="text" name="seo_title[{{ $language->slug }}]" id="seo_title-{{ $language->slug }}" value="{{ old('seo_title.' . $language->slug, $translation->seo_title ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm">
                        </div>
                        <div>
                            <label for="seo_description-{{ $language->slug }}" class="block text-sm font-medium text-gray-700">{{ __('SEO Description') }} ({{ strtoupper($language->slug) }})</label>
                            <textarea name="seo_description[{{ $language->slug }}]" id="seo_description-{{ $language->slug }}" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm">{{ old('seo_description.' . $language->slug, $translation->seo_description ?? '') }}</textarea>
                        </div>
                        <div>
                            <label for="seo_keywords-{{ $language->slug }}" class="block text-sm font-medium text-gray-700">{{ __('SEO Keywords') }} ({{ strtoupper($language->slug) }})</label>
                            <input type="text" name="seo_keywords[{{ $language->slug }}]" id="seo_keywords-{{ $language->slug }}" value="{{ old('seo_keywords.' . $language->slug, $translation->seo_keywords ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">Separate keywords with commas.</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end items-center mt-8 border-t border-gray-200 pt-6">
                <a href="{{ route('tags.index') }}" class="text-gray-600 px-4 py-2 rounded-md mr-2 hover:bg-gray-100 transition">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="bg-black text-white px-5 py-2 rounded-md font-normal hover:bg-gray-800 transition">
                    {{ __('Update Tag') }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const target = document.getElementById(tab.dataset.target);
                    tabs.forEach(t => {
                        t.classList.remove('border-black', 'text-black');
                        t.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                    });
                    tabContents.forEach(c => c.style.display = 'none');
                    tab.classList.add('border-black', 'text-black');
                    tab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                    if (target) {
                        target.style.display = 'block';
                    }
                });
            });
        });
    </script>
    @endpush
@endsection