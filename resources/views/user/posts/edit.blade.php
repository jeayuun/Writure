<x-app-layout>
    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        .note-editor.note-frame {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
        }
        .note-toolbar {
            border-bottom: 1px solid #e5e7eb;
            background-color: #f9fafb;
        }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('user.posts.update', $post) }}" id="post-form" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <input type="hidden" name="remove_cover_image" id="remove_cover_image_input" value="0">
                
                <div class="flex flex-col space-y-8">
                    {{-- Cover Image Preview --}}
                    <img id="cover-image-preview" src="{{ $post->cover_image ? asset($post->cover_image) : '' }}" alt="Cover image preview" class="rounded-lg mb-4 @if(!$post->cover_image) hidden @endif">

                    {{-- Title Input --}}
                    <textarea
                        id="title"
                        name="title"
                        rows="1"
                        class="w-full text-5xl font-serif border-none focus:ring-0 resize-none p-0 placeholder-gray-400"
                        placeholder="Type your title here..."
                        oninput="this.style.height = 'auto'; this.style.height = (this.scrollHeight) + 'px';"
                        required
                    >{{ old('title', $translation->title ?? '') }}</textarea>

                    {{-- Cover Image Buttons --}}
                    <div id="cover-image-buttons">
                        <input type="file" name="cover_image" id="cover_image" class="hidden" onchange="previewImage(event)">
                        
                        <button type="button" id="add-cover-button" onclick="document.getElementById('cover_image').click()" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50 transition @if($post->cover_image) hidden @endif">
                            Add Cover Image
                        </button>

                        <div id="change-remove-buttons" class="flex items-center space-x-2 @if(!$post->cover_image) hidden @endif">
                            <button type="button" onclick="document.getElementById('cover_image').click()" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50 transition">
                                Change
                            </button>
                            <button type="button" onclick="removeImage()" class="text-red-600 hover:text-red-800 text-sm font-medium transition">
                                Remove
                            </button>
                        </div>
                    </div>

                    {{-- Content Editor --}}
                    <textarea id="content" name="content" class="summernote" required>{{ old('content', $translation->content ?? '') }}</textarea>
                </div>
            </form>
        </div>
    </div>

    {{-- Bottom Action Bar --}}
    <footer class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-end items-center h-20">
                <a href="{{ route('dashboard') }}" class="text-gray-600 px-4 py-2 rounded-md mr-2 hover:bg-gray-100 transition">
                    Cancel
                </a>
                <button type="submit" form="post-form" class="border border-gray-800 text-white bg-black px-8 py-2 rounded-full font-semibold hover:bg-gray-800 transition text-m">
                    Update Post
                </button>
            </div>
        </div>
    </footer>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                placeholder: 'Start writing your story here...',
                tabsize: 2,
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['codeview']]
                ]
            });

            // Auto-resize title textarea on page load
            const titleTextarea = document.getElementById('title');
            if (titleTextarea.value) {
                titleTextarea.style.height = 'auto';
                titleTextarea.style.height = (titleTextarea.scrollHeight) + 'px';
            }
        });

        function previewImage(event) {
            // Ensure we don't remove the image if a new one is being uploaded
            document.getElementById('remove_cover_image_input').value = '0'; 
            
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('cover-image-preview');
                output.src = reader.result;
                output.classList.remove('hidden');

                document.getElementById('add-cover-button').classList.add('hidden');
                document.getElementById('change-remove-buttons').classList.remove('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function removeImage() {
            // Signal to the backend to remove the image from the database
            document.getElementById('remove_cover_image_input').value = '1';

            const output = document.getElementById('cover-image-preview');
            const fileInput = document.getElementById('cover_image');
            
            output.src = '';
            output.classList.add('hidden');
            fileInput.value = ''; // Clear the file input

            document.getElementById('add-cover-button').classList.remove('hidden');
            document.getElementById('change-remove-buttons').classList.add('hidden');
        }
    </script>
    @endpush
</x-app-layout>