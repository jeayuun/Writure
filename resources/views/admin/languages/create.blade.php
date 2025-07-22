@extends('layouts.admin')

@section('content')
    <div class="bg-white p-8 rounded-lg shadow-sm">
        <h1 class="text-2xl font-semibold font-serif text-gray-800 mb-6">{{ __('Add New Language') }}</h1>

        <form action="{{ route('languages.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Language Name') }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm"
                           placeholder="e.g., English" required>
                </div>

                {{-- Slug --}}
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700">{{ __('Slug (e.g., en)') }}</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm"
                           placeholder="e.g., en, fr, ph" required>
                </div>

                {{-- Flag --}}
                <div>
                    <label for="flag" class="block text-sm font-medium text-gray-700">{{ __('Flag (Emoji or Text)') }}</label>
                    <input type="text" name="flag" id="flag" value="{{ old('flag') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm"
                           placeholder="e.g., 🇬🇧 or gb">
                </div>

                {{-- Toggles --}}
                <div class="space-y-4 pt-4 border-t border-gray-200">
                    <label class="flex items-center">
                        <input type="hidden" name="is_default" value="0">
                        <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-black focus:ring-black">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Set as default language') }}</span>
                    </label>
                    <label class="flex items-center">
                         <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-black focus:ring-black">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Active (Visible on site)') }}</span>
                    </label>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end items-center mt-8 border-t border-gray-200 pt-6">
                <a href="{{ route('languages.index') }}" class="text-gray-600 px-4 py-2 rounded-md mr-2 hover:bg-gray-100 transition">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="bg-black text-white px-5 py-2 rounded-md font-normal hover:bg-gray-800 transition">
                    {{ __('Save Language') }}
                </button>
            </div>
        </form>
    </div>
@endsection
