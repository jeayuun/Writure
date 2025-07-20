@extends('frontend.layouts.app')

@section('title', __('Create New Post'))

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                <h1 class="text-2xl font-bold mb-6">{{ __('Create New Post') }}</h1>

                <form action="{{ route('user.posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Left Column: General Info -->
                        <div class="lg:col-span-1 space-y-6">
                            <div class="p-6 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <h3 class="text-lg font-semibold mb-4">General Information</h3>
                                <!-- Category -->
                                <div class="mb-4">
                                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category:</label>
                                    <select name="category_id" id="category_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-800 dark:border-gray-600">
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->translate($lang)->name ?? $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Tags -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tags:</label>
                                    <div class="mt-2 space-y-2">
                                        @foreach($tags as $tag)
                                            <label class="inline-flex items-center mr-4">
                                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $tag->translate($lang)->name ?? $tag->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Cover Image -->
                                <div class="mb-4">
                                    <label for="cover_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cover Image:</label>
                                    <input type="file" name="cover_image" id="cover_image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/40 dark:file:text-blue-300 dark:hover:file:bg-blue-900/60">
                                </div>
                                <!-- Gallery Photos -->
                                <div class="mb-4">
                                     <label for="gallery" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gallery Photos:</label>
                                     <div id="gallery-dropzone" class="dropzone mt-1 border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                         <div class="dz-message">Drag and drop files or click to upload</div>
                                     </div>
                                </div>
                                <!-- Order -->
                                <div class="mb-4">
                                    <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Order:</label>
                                    <input type="number" name="order" id="order" value="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600">
                                </div>
                                <!-- Checkboxes -->
                                <div class="flex items-center space-x-6">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_featured" value="1" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Is Featured</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="comments_enabled" value="1" checked class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Comments Enabled</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Multi-language content -->
                        <div class="lg:col-span-2">
                            <div class="p-6 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <h3 class="text-lg font-semibold mb-4">Multi-Language Content</h3>
                                <!-- Language Tabs -->
                                <div x-data="{ activeTab: '{{ $languages->first()->slug }}' }">
                                    <div class="border-b border-gray-200 dark:border-gray-600">
                                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                            @foreach ($languages as $language)
                                                <button type="button" @click="activeTab = '{{ $language->slug }}'"
                                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === '{{ $language->slug }}', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== '{{ $language->slug }}' }"
                                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                                    {{ $language->name }}
                                                </button>
                                            @endforeach
                                        </nav>
                                    </div>

                                    <!-- Tab Panels -->
                                    @foreach ($languages as $language)
                                        <div x-show="activeTab === '{{ $language->slug }}'" class="pt-6 space-y-4">
                                            <!-- Title -->
                                            <div class="mb-4">
                                                <label for="title_{{ $language->slug }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title:</label>
                                                <input type="text" name="translations[{{ $language->slug }}][title]" id="title_{{ $language->slug }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600">
                                            </div>
                                            <!-- Slug -->
                                            <div class="mb-4">
                                                <label for="slug_{{ $language->slug }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug:</label>
                                                <input type="text" name="translations[{{ $language->slug }}][slug]" id="slug_{{ $language->slug }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600">
                                            </div>
                                            <!-- Short Description -->
                                            <div class="mb-4">
                                                <label for="short_description_{{ $language->slug }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Short Description:</label>
                                                <textarea name="translations[{{ $language->slug }}][short_description]" id="short_description_{{ $language->slug }}" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600"></textarea>
                                            </div>
                                            <!-- Content -->
                                            <div class="mb-4">
                                                <label for="content_{{ $language->slug }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content:</label>
                                                <textarea name="translations[{{ $language->slug }}][content]" id="content_{{ $language->slug }}" class="tinymce-editor mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600"></textarea>
                                            </div>
                                            <!-- SEO Fields -->
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <label for="seo_title_{{ $language->slug }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SEO Title:</label>
                                                    <input type="text" name="translations[{{ $language->slug }}][seo_title]" id="seo_title_{{ $language->slug }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:border-gray-600">
                                                </div>
                                                <div>
                                                    <label for="seo_description_{{ $language->slug }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SEO Description:</label>
                                                    <input type="text" name="translations[{{ $language->slug }}][seo_description]" id="seo_description_{{ $language->slug }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:border-gray-600">
                                                </div>
                                                <div>
                                                    <label for="seo_keywords_{{ $language->slug }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SEO Keywords:</label>
                                                    <input type="text" name="translations[{{ $language->slug }}][seo_keywords]" id="seo_keywords_{{ $language->slug }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:border-gray-600">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-8">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition">
                            Submit Post for Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<!-- Dropzone -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize TinyMCE
        tinymce.init({
            selector: 'textarea.tinymce-editor',
            plugins: 'code table lists image media link',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image media link',
            skin: (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'oxide-dark' : 'oxide'),
            content_css: (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'default')
        });

        // Initialize Dropzone
        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone("#gallery-dropzone", {
            url: "{{ route('user.posts.store') }}", // The URL will be handled by the form submission
            autoProcessQueue: false, // We will handle the upload with the form submission
            uploadMultiple: true,
            parallelUploads: 10,
            maxFiles: 10,
            addRemoveLinks: true,
            paramName: "gallery", // The name of the file input field
            init: function() {
                var myDropzone = this;
                var form = document.querySelector("form");

                form.addEventListener("submit", function(e) {
                    if (myDropzone.getQueuedFiles().length > 0) {
                        e.preventDefault();
                        myDropzone.processQueue();
                    }
                });

                this.on("sendingmultiple", function(file, xhr, formData) {
                    // Append all form data to the request
                    var data = new FormData(form);
                    for (var key of data.keys()) {
                        formData.append(key, data.get(key));
                    }
                });

                this.on("successmultiple", function(files, response) {
                    // Handle success, maybe redirect
                    window.location.href = "{{ route('user.dashboard') }}";
                });

                this.on("errormultiple", function(files, response) {
                    // Handle error
                    console.error('Error uploading files:', response);
                    alert('There was an error uploading your gallery images.');
                });
            }
        });
    });
</script>
@endpush
