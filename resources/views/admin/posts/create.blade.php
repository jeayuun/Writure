@extends('layouts.admin')

@push('styles')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        .note-editor.note-frame {
            border-radius: 0.375rem;
            border-color: #d1d5db;
        }
        .note-toolbar {
            border-top-left-radius: 0.375rem;
            border-top-right-radius: 0.375rem;
            background-color: #f9fafb;
        }
    </style>
@endpush

@section('content')
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold font-serif text-gray-800">{{ __('Add New Post') }}</h1>
            <div class="flex items-center">
                <a href="{{ route('posts.index') }}" class="text-gray-600 px-4 py-2 rounded-md mr-2 hover:bg-gray-100 transition">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="bg-black text-white px-5 py-2 rounded-md font-normal hover:bg-gray-800 transition">
                    <i class="fas fa-save mr-2"></i> {{ __('Save Post') }}
                </button>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6" role="alert">
                <p class="font-bold">Please fix the following errors:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="list-disc ml-5">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content Column --}}
            <div class="lg:col-span-2">
                <div class="bg-white p-8 rounded-lg shadow-sm">
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

                        <div class="mt-6">
                            @foreach ($languages as $lang)
                                <div x-show="activeTab === '{{ $lang->slug }}'" x-cloak class="space-y-6">
                                    {{-- Title --}}
                                    <div>
                                        <label for="title_{{ $lang->slug }}" class="block text-sm font-medium text-gray-700">{{ __('Title') }}</label>
                                        <input type="text" name="translations[{{ $lang->slug }}][title]" id="title_{{ $lang->slug }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm" placeholder="{{ __('Enter title') }}" {{ $lang->is_default ? 'required' : '' }}>
                                    </div>
                                    {{-- Slug --}}
                                    <div>
                                        <label for="slug_{{ $lang->slug }}" class="block text-sm font-medium text-gray-700">{{ __('Slug') }}</label>
                                        <input type="text" name="translations[{{ $lang->slug }}][slug]" id="slug_{{ $lang->slug }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm" placeholder="{{ __('seo-friendly-url') }}" {{ $lang->is_default ? 'required' : '' }}>
                                    </div>
                                    {{-- Short Description --}}
                                    <div>
                                        <label for="short_description_{{ $lang->slug }}" class="block text-sm font-medium text-gray-700">{{ __('Short Description') }}</label>
                                        <textarea name="translations[{{ $lang->slug }}][short_description]" id="short_description_{{ $lang->slug }}" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm" placeholder="{{ __('Summary of the post') }}"></textarea>
                                    </div>
                                    {{-- Content --}}
                                    <div>
                                        <label for="content_{{ $lang->slug }}" class="block text-sm font-medium text-gray-700">{{ __('Content') }}</label>
                                        <textarea name="translations[{{ $lang->slug }}][content]" id="content_{{ $lang->slug }}" class="summernote"></textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Column --}}
            <div class="space-y-8">
                <div class="bg-white p-8 rounded-lg shadow-sm">
                    <h2 class="text-xl font-semibold font-serif text-gray-800 mb-4">{{ __('Details') }}</h2>
                     <!-- Status -->
                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status') }}:</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm">
                            <option value="draft">{{ __('Draft') }}</option>
                            <option value="published">{{ __('Published') }}</option>
                        </select>
                    </div>
                     <!-- Category -->
                    <div class="space-y-2 mt-6">
                        <label for="category_id" class="block text-sm font-medium text-gray-700">{{ __('Category') }}:</label>
                        <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm">
                            <option value="">{{ __('Select a category') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->translations->first()?->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-sm">
                     <h2 class="text-xl font-semibold font-serif text-gray-800 mb-4">{{ __('Featured Image') }}</h2>
                     <div class="space-y-2">
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="cover_image" class="relative cursor-pointer bg-white rounded-md font-medium text-black hover:text-gray-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-black">
                                        <span>Upload a file</span>
                                        <input id="cover_image" name="cover_image" type="file" class="sr-only" onchange="previewCover(event)">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                        </div>
                        <img src="" class="mt-4 h-48 w-full object-cover rounded-lg shadow hidden" id="cover-preview">
                    </div>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-sm">
                    <h2 class="text-xl font-semibold font-serif text-gray-800 mb-4">{{ __('Tags') }}</h2>
                    <div class="space-y-2">
                        <div class="flex flex-wrap gap-2 border rounded-lg p-2">
                            @foreach ($tags as $tag)
                                <label class="flex items-center space-x-2 bg-gray-100 px-3 py-1 rounded-full hover:bg-gray-200 transition cursor-pointer">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="rounded text-black focus:ring-black h-4 w-4">
                                    <span class="text-sm text-gray-700">{{ $tag->translations->first()?->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });
        });

        function previewCover(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('cover-preview');
                if (preview) {
                    preview.src = reader.result;
                    preview.classList.remove('hidden');
                }
            };
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
@endpush
