@extends('layouts.admin')

@section('content')
    <div class="bg-white p-8 rounded-lg shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold font-serif text-gray-800">{{ __('Category List') }}</h1>
            <a href="{{ route('categories.create') }}" class="bg-black text-white px-5 py-2 rounded-md font-normal hover:bg-gray-800 transition">
                <i class="fas fa-plus mr-2"></i> {{ __('Add New Category') }}
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
                    @foreach($categories as $category)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $category->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap hover:bg-gray-50 transition">
                                @if($category->translations->where('language_slug', $defaultLanguageSlug)->isNotEmpty())
                                    <div class="font-medium text-gray-800">{{ $category->translations->where('language_slug', $defaultLanguageSlug)->first()->name }}</div>
                                @else
                                    <div class="text-sm text-gray-500">({{ __('No translation in default language') }})</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('categories.edit', $category) }}"  style="display: inline-block; padding: 8px 24px; border: 1px solid #d1d5db; border-radius: 9999px; color: #374151; background-color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 500;">{{ __('Edit') }}</a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this item?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="display: inline-block; padding: 8px 24px; border: 1px solid #d1d5db; border-radius: 9999px; color: #374151; background-color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 500;">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if($categories->isEmpty())
                        <tr>
                            <td colspan="3" class="text-center px-6 py-4 text-gray-500">{{ __('No categories found.') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        @if ($categories->hasPages())
            <div class="mt-6">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection