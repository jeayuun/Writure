<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-16">
                <div class="flex items-center space-x-6">
                    <img class="h-24 w-24 rounded-full object-cover" src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://placehold.co/256x256/EFEFEF/333333?text=' . substr($user->name, 0, 1) }}" alt="{{ $user->name }}">
                    <div>
                        <h1 class="text-4xl font-serif text-gray-800">{{ $user->name }}</h1>
                    </div>
                </div>
                <div>
                    <a href="{{ route('user.post.create') }}" class="border border-gray-800 text-white bg-black px-6 py-3 rounded-full font-semibold hover:bg-white hover:text-black transition text-m">Start Writing</a>
                </div>
            </div>

            @if($posts->isEmpty())
                <div class="text-center py-24">
                    <p class="text-gray-500 mb-4">Welcome, you haven't written any blogs yet.</p>
                    <a href="{{ route('user.post.create') }}" class="text-black font-semibold hover:underline">Start Writing</a>
                </div>
            @else   
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($posts as $post)
                        <div class="bg-white rounded-lg overflow-hidden flex flex-col group">
                            @if($post->cover_image)
                                <a href="#">
                                    <img src="{{ asset($post->cover_image) }}" alt="{{ $post->translations->first()->title ?? '' }}" class="w-full h-56 object-cover">
                                </a>
                            @endif
                            <div class="p-6 flex flex-col flex-grow">
                                <div class="flex-grow">
                                    <h3 class="font-semibold text-xl mb-2 text-gray-800">{{ $post->translations->first()->title ?? '' }}</h3>
                                    <p class="text-gray-600 text-sm">{{ $post->translations->first()->short_description ?? '' }}</p>
                                </div>
                                <div class="mt-6 flex justify-end space-x-2">
                                     <a href="#" class="transition" style="display: inline-block; padding: 8px 24px; border: 1px solid #d1d5db; border-radius: 9999px; color: #374151; background-color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 500;">Edit</a>
                                     <form action="#" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="transition" style="display: inline-block; padding: 8px 24px; border: 1px solid #d1d5db; border-radius: 9999px; color: #374151; background-color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 500;">Delete</button>
                                     </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                 <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
