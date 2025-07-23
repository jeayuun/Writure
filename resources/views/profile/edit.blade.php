<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-8">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-8">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
