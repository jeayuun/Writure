@extends('layouts.admin')

@section('content')
    <div class="bg-white p-8 rounded-lg shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold font-serif text-gray-800">{{ __('Tag List') }}</h1>
            <a href="{{ route('tags.create') }}" class="bg-black text-white px-5 py-2 rounded-md font-normal hover:bg-gray-800 transition">
                <i class="fas fa-plus mr-2"></i> {{ __('Add New Tag') }}
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ID') }}</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($tags as $tag)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $tag->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap hover:bg-gray-50 transition">
                                @foreach($tag->translations as $trans)
                                    @if($trans->language_slug === $langSlug)
                                        <div class="font-medium text-gray-800">{{ $trans->name }}</div>
                                    @endif
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('tags.edit', $tag->id) }}" style="display: inline-block; padding: 8px 24px; border: 1px solid #d1d5db; border-radius: 9999px; color: #374151; background-color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 500;">{{ __('Edit') }}</a>
                                <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="display: inline-block; padding: 8px 24px; border: 1px solid #d1d5db; border-radius: 9999px; color: #374151; background-color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 500; cursor: pointer;">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if($tags->isEmpty())
                        <tr>
                            <td colspan="3" class="text-center px-6 py-4 text-gray-500">{{ __('No tags found.') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if ($tags->hasPages())
            <div class="mt-6">
                {{ $tags->links() }}
            </div>
        @endif
    </div>
@endsection
