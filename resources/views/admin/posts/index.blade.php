@extends('layouts.admin')

@section('content')
    <div class="bg-white p-8 rounded-lg shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold font-serif text-gray-800">{{ __('Post List') }}</h1>
            <a href="{{ route('posts.create') }}" class="bg-black text-white px-5 py-2 rounded-md font-normal hover:bg-gray-800 transition">
                <i class="fas fa-plus mr-2"></i> {{ __('Add New Post') }}
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ID') }}</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Title') }}</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Category') }}</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($posts as $post)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $post->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">
                                {{ $post->translations->where('language_slug', $langSlug)->first()?->title ?? __('No translation') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                {{ $post->category?->translations->where('language_slug', $langSlug)->first()?->name ?? __('No category') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <form action="{{ route('posts.status', $post->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit"
                                            name="status"
                                            value="{{ $post->status === 'published' ? 'draft' : 'published' }}"
                                             style="display: inline-block; padding: 8px 24px; border: 1px solid #d1d5db; border-radius: 9999px;  text-decoration: none; font-size: 16px; font-weight: 500;"
                                            class="font-semibold
                                                   {{ $post->status === 'published' ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-200 text-gray-700 hover:bg-gray-200' }}">
                                        {{ $post->status === 'published' ? __('Published') : __('Draft') }}
                                    </button>
                                </form>

                                <a href="{{ route('posts.edit', $post->id) }}" style="display: inline-block; padding: 8px 24px; border: 1px solid #d1d5db; border-radius: 9999px; color: #374151; background-color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 500;">{{ __('Edit') }}</a>

                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="display: inline-block; padding: 8px 24px; border: 1px solid #d1d5db; border-radius: 9999px; color: #374151; background-color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 500;">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if ($posts->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center px-6 py-4 text-gray-500">{{ __('No posts have been added yet.') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if ($posts->hasPages())
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
