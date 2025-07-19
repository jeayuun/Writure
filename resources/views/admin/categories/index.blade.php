@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto mt-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">{{ __('Category List') }}</h2>
            <a href="{{ route('categories.create') }}"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                + {{ __('Add New Category') }}
            </a>
        </div>

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100 text-gray-700 text-sm">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">{{ __('Languages') }}</th>
                        <th class="px-4 py-2 text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($categories as $category)
                        <tr>
                            <td class="px-4 py-2">{{ $category->id }}</td>
                            <td class="px-4 py-2">
                                @foreach($category->translations as $trans)
                                @if($trans->language_slug === $langSlug)
                                    <div><span class="font-semibold">{{ strtoupper($trans->language_slug) }}:</span> {{ $trans->name }}</div>
                                @endif
                                @endforeach
                            </td>
                            <td class="px-4 py-2 text-center space-x-2">
                                <a href="{{ route('categories.edit', $category->id) }}"
                                   class="text-blue-600 hover:underline">{{ __('Edit') }}</a>

                                <form action="{{ route('categories.destroy', $category->id) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this item?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if($categories->isEmpty())
                        <tr>
                            <td colspan="3" class="text-center px-4 py-6 text-gray-500">{{ __('No categories found.') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
