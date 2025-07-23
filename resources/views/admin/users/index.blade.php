@extends('layouts.admin')

@section('content')
    <div class="bg-white p-8 rounded-lg shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold font-serif text-gray-800">{{ __('User List') }}</h1>
            <a href="{{ route('users.create') }}" class="bg-black text-white px-5 py-2 rounded-md font-normal hover:bg-gray-800 transition">
                <i class="fas fa-plus mr-2"></i> {{ __('Add New User') }}
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('User') }}</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Username') }}</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Role') }}</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://placehold.co/40x40/EFEFEF/333333?text=' . substr($user->name, 0, 1) }}" alt="{{ $user->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->is_admin)
                                    <span class="bg-purple-100 text-purple-800" style="display: inline-block; padding: 8px 24px; border: 1px solid #d1d5db; border-radius: 9999px; text-decoration: none; font-size: 16px; font-weight: 500;">
                                        Admin
                                    </span>
                                @else
                                    <span class="bg-blue-100 text-blue-800" style="display: inline-block; padding: 8px 24px; border: 1px solid #d1d5db; border-radius: 9999px; text-decoration: none; font-size: 16px; font-weight: 500;">
                                        User
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('users.edit', $user->id) }}" style="display: inline-block; padding: 8px 24px; border: 1px solid #d1d5db; border-radius: 9999px; color: #374151; background-color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 500;">{{ __('Edit') }}</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="display: inline-block; padding: 8px 24px; border: 1px solid #d1d5db; border-radius: 9999px; color: #374151; background-color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 500;">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                {{ __('No users found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
