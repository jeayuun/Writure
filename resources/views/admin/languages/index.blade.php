@extends('layouts.admin')

@section('content')
    <div class="bg-white p-8 rounded-lg shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold font-serif text-gray-800">{{ __('Language List') }}</h1>
            <a href="{{ route('languages.create') }}" class="bg-black text-white px-5 py-2 rounded-md font-normal hover:bg-gray-800 transition">
                <i class="fas fa-plus mr-2"></i> {{ __('Add New Language') }}
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ID') }}</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Flag') }}</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Slug') }}</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">{{ __('Default') }}</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($languages as $language)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $language->id }}</td>
                            {{-- Using the slug for the flag as per the design image --}}
                            <td class="px-6 py-4 whitespace-nowrap font-mono text-gray-700">{{ $language->slug }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">{{ $language->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $language->slug }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($language->is_default)
                                    <span style="display: inline-block; padding: 8px 24px; border: 1px solid #d1d5db; border-radius: 9999px; text-decoration: none; font-size: 16px; font-weight: 500;" 
                                        class="bg-green-100 text-green-800">
                                        Yes
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($language->status)
                                    <span style="display: inline-block; padding: 8px 24px; border: 1px solid #d1d5db; border-radius: 9999px; text-decoration: none; font-size: 16px; font-weight: 500;" 
                                        class="bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                     <span style="display: inline-block; padding: 8px 20px; border: 1px solid #d1d5db; border-radius: 9999px; text-decoration: none; font-size: 16px; font-weight: 500;" 
                                        class="bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('languages.edit', $language->id) }}" style="display: inline-block; padding: 8px 24px; border: 1px solid #d1d5db; border-radius: 9999px; color: #374151; background-color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 500;">{{ __('Edit') }}</a>
                                <form action="{{ route('languages.destroy', $language->id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this item?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="display: inline-block; padding: 8px 24px; border: 1px solid #d1d5db; border-radius: 9999px; color: #374151; background-color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 500;">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if($languages->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center px-6 py-4 text-gray-500">{{ __('No languages found.') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
