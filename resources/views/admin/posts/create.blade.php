@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto mt-10">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- General Information Section -->
            <div class="bg-white p-6 shadow rounded-lg space-y-4">
                <h2 class="text-xl font-semibold mb-4">{{ __('General Information') }}</h2>

                <!-- Category Selection -->
                <div class="space-y-2">
                    <label class="block text-gray-700">{{ __('Category') }}:</label>
                    <select name="category_id" class="border rounded-lg w-full px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ __('Select a category') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->translations->where('language_slug', app()->getLocale())->first()?->name ?? $category->translations->first()?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tags -->
                <div class="space-y-2">
                    <label class="block text-gray-700">{{ __('Tags') }}:</label>
                    <div class="flex flex-wrap gap-2 border rounded-lg p-2">
                        @foreach ($tags as $tag)
                            <label
                                class="flex items-center space-x-2 bg-gray-100 px-3 py-1 rounded-full hover:bg-blue-100 transition">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                    class="rounded text-blue-600 focus:ring-blue-500">
                                <span
                                    class="text-sm text-gray-700">{{ $tag->translations->where('language_slug', app()->getLocale())->first()?->name ?? $tag->translations->first()?->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Cover Image -->
                <div class="space-y-2">
                    <label class="block text-gray-700">{{ __('Cover Image') }}:</label>
                    <div class="flex items-center space-x-4">
                        <div class="relative group">
                            <img src="" class="h-32 w-32 object-cover rounded-lg shadow hidden" id="cover-preview">
                            <button type="button"
                                onclick="document.getElementById('cover_image').value = ''; document.getElementById('cover-preview').src = ''"
                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition hidden">
                                ✕
                            </button>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="cover_image" id="cover_image"
                                class="border rounded-lg w-full px-3 py-2" onchange="previewCover(event)">
                        </div>
                    </div>
                </div>

                <!-- Gallery Photos -->
                <div class="space-y-2">
                    <label class="block text-gray-700">{{ __('Gallery Photos') }}:</label>
                    <div class="border-2 border-dashed rounded-lg p-4 text-center cursor-pointer" id="gallery-dropzone"
                        ondragover="event.preventDefault(); this.classList.add('border-blue-500')"
                        ondragleave="this.classList.remove('border-blue-500')" ondrop="handleDrop(event)">
                        <p class="text-gray-500 mb-2">{{ __('Drag and drop files or click to upload') }}</p>
                        <input type="file" name="gallery_images[]" multiple class="hidden" id="gallery-input"
                            onchange="previewGallery(event)">
                        <button type="button" onclick="document.getElementById('gallery-input').click()"
                            class="bg-blue-100 text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-200 transition">
                            {{ __('Select Files') }}
                        </button>
                    </div>

                    <!-- Gallery Preview -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4" id="gallery-preview">
                        <!-- New uploaded images will be shown here -->
                    </div>
                </div>

                <!-- Other Fields -->
                <div class="space-y-2">
                    <label class="block text-gray-700">{{ __('Order') }}:</label>
                    <input type="number" name="order" value="{{ old('order') }}"
                        class="border rounded-lg w-full px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="space-y-2">
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" name="is_featured" value="1"
                            class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                        <span class="text-gray-700">{{ __('Is Featured') }}</span>
                    </label>

                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" name="comment_enabled" value="1" checked
                            class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                        <span class="text-gray-700">{{ __('Comments Enabled') }}</span>
                    </label>
                </div>

                <div class="space-y-2">
                    <label class="block text-gray-700">{{ __('Status') }}:</label>
                    <select name="status" class="border rounded-lg w-full px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="draft">{{ __('Draft') }}</option>
                        <option value="published">{{ __('Published') }}</option>
                    </select>
                </div>
            </div>

            <!-- Multi-Language Content Section -->
            <div class="bg-white p-6 shadow rounded-lg space-y-4">
                <h2 class="text-xl font-semibold mb-4">{{ __('Multi-Language Content') }}</h2>

                @foreach ($languages as $lang)
                    @if ($lang->is_default === 1)
                        <div class="border border-gray-200 rounded-lg p-4 space-y-3 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-700">{{ strtoupper($lang->name) }}</h3>

                            <!-- Title -->
                            <div class="space-y-1">
                                <label class="block text-gray-700">{{ __('Title') }}:</label>
                                <input type="text" name="translations[{{ $lang->slug }}][title]"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                    placeholder="{{ __('Enter title') }}" required>
                            </div>

                            <!-- Slug -->
                            <div class="space-y-1">
                                <label class="block text-gray-700">{{ __('Slug') }}:</label>
                                <input type="text" name="translations[{{ $lang->slug }}][slug]"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                    placeholder="{{ __('SEO-friendly URL') }}" required>
                            </div>

                            <!-- Short Description -->
                            <div class="space-y-1">
                                <label class="block text-gray-700">{{ __('Short Description') }}:</label>
                                <textarea name="translations[{{ $lang->slug }}][short_description]"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" rows="3"
                                    placeholder="{{ __('Summary information') }}"></textarea>
                            </div>

                            <!-- Content -->
                            <div class="space-y-1">
                                <label class="block text-gray-700">{{ __('Content') }}:</label>
                                <textarea name="translations[{{ $lang->slug }}][content]"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" rows="6"
                                    placeholder="{{ __('Detailed content') }}" id="content" spellcheck="false" autocomplete="off"></textarea>
                            </div>

                            <!-- SEO Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="space-y-1">
                                    <label class="block text-gray-700">{{ __('SEO Title') }}:</label>
                                    <input type="text" name="translations[{{ $lang->slug }}][seo_title]"
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                        placeholder="{{ __('Meta title') }}">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-gray-700">{{ __('SEO Description') }}:</label>
                                    <input type="text" name="translations[{{ $lang->slug }}][seo_description]"
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                        placeholder="{{ __('Meta description') }}">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-gray-700">{{ __('SEO Keywords') }}:</label>
                                    <input type="text" name="translations[{{ $lang->slug }}][seo_keywords]"
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                        placeholder="{{ __('Meta keywords') }}">
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition w-full md:w-auto">
                {{ __('Save') }}
            </button>
        </form>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs/Sortable.min.js"></script>
        <script>
            $('#content').summernote({
                height: 400,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'video']],
                    ['view', ['codeview']]
                ]
            });

            // Cover Image Preview
            function previewCover(event) {
                const reader = new FileReader();
                reader.onload = function() {
                    const preview = document.getElementById('cover-preview');
                    const removeBtn = preview.nextElementSibling;
                    if (preview) {
                        preview.src = reader.result;
                        preview.classList.remove('hidden');
                        removeBtn.classList.remove('hidden');
                    }
                };
                if (event.target.files[0]) {
                    reader.readAsDataURL(event.target.files[0]);
                }
            }

            // Gallery Management
            function previewGallery(event) {
                const files = event.target.files;
                const previewContainer = document.getElementById('gallery-preview');

                for (let file of files) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg shadow">
                        <button 
                            type="button" 
                            onclick="this.parentElement.remove()" 
                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition"
                        >
                            ✕
                        </button>
                    `;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            }

            // Drag-and-Drop Functionality
            function handleDrop(e) {
                e.preventDefault();
                e.target.classList.remove('border-blue-500');
                const files = e.dataTransfer.files;
                const input = document.getElementById('gallery-input');
                input.files = files;
                previewGallery({
                    target: input
                });
            }

            // Sortable.js for ordering
            document.addEventListener('DOMContentLoaded', function() {
                const galleryPreview = document.getElementById('gallery-preview');

                new Sortable(galleryPreview, {
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    handle: '.group'
                });
            });
        </script>
    @endpush

    @push('styles')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
        <style>
            #gallery-dropzone {
                transition: border-color 0.3s ease, background-color 0.3s ease;
            }

            #gallery-dropzone:hover {
                border-color: #3B82F6;
                background-color: #f8fafc;
            }

            .sortable-ghost {
                opacity: 0.5;
                background: #c8ebfb;
            }
        </style>
    @endpush
@endsection
