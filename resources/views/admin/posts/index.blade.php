@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto mt-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">{{ __('Post List') }}</h2>
            <a href="{{ route('posts.create') }}"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                + {{ __('Add New Post') }}
            </a>
        </div>

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">{{ __('Title') }}</th>
                        <th class="px-4 py-2 text-left">{{ __('Category') }}</th>
                        <th class="px-4 py-2 text-left">{{ __('Languages') }}</th>
                        <th class="px-4 py-2 text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($posts as $post)
                        <tr>
                            <td class="px-4 py-2">{{ $post->id }}</td>

                            <td class="px-4 py-2">
                                @if ($post->translations->where('language_slug', $langSlug)->first()?->title)
                                    {{ $post->translations->where('language_slug', $langSlug)->first()?->title }}
                                @else
                                    <div class="text-gray-500">{{ __('No translations available') }}</div>
                                @endif
                            </td>

                            <td class="px-4 py-2">
                                @if ($post->category && $post->category->translations->isNotEmpty())
                                    {{ $post->category->translations->where('language_slug', $langSlug)->first()?->name }}
                                @else
                                    <div class="text-gray-500">{{ __('No category') }}</div>
                                @endif
                            </td>

                            <td class="px-4 py-2">
                                @foreach ($languages as $language)
                                    @php
                                        $hasTranslation = $post->translations
                                            ->where('language_slug', $language->slug)
                                            ->isNotEmpty();
                                    @endphp
                                    <a href="{{ route('posts.editLang', [$post->id, $language->slug]) }}"
                                        class="text-blue-600 hover:underline">
                                        <div
                                            class="inline-block {{ $hasTranslation ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }} text-xs px-2 py-1 rounded mb-1">
                                            {{ $language->slug }}
                                        </div>
                                    </a>
                                @endforeach
                            </td>

                            <td class="px-4 py-2 text-center space-x-2">
                                <a href="{{ route('posts.edit', $post->id) }}"
                                    class="text-blue-600 hover:underline">{{ __('Edit') }}</a>
                                <form action="{{ route('posts.status', $post->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit"
                                        class="px-2 py-1 rounded {{ $post->status === 'published' ? 'bg-green-500 text-white' : 'bg-gray-500 text-white' }} hover:opacity-90"
                                        name="status" value="{{ $post->status === 'published' ? 'draft' : 'published' }}">
                                        {{ $post->status === 'published' ? __('Published') : __('Draft') }}
                                    </button>
                                </form>

                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('{{ __('Are you sure you want to delete this?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if ($posts->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center px-4 py-6 text-gray-500">{{ __('No posts have been added yet.') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            {{ $posts->links() }}
        </div>
    </div>
@endsection
