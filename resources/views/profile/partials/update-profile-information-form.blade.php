<section>
    <header>
        <h2 class="text-2xl font-semibold font-serif text-gray-800">
            {{ __('Account Settings') }}
        </h2>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-6" enctype="multipart/form-data" x-data="{photoName: null, photoPreview: null}">
        @csrf
        @method('patch')

        <!-- Profile Photo -->
        <div class="grid grid-cols-3 gap-6 items-center">
            <label class="text-sm font-medium text-gray-700">{{ __('Photo') }}</label>
            <div class="col-span-2 flex items-center space-x-4">
                <div class="shrink-0">
                    <img class="h-16 w-16 rounded-full object-cover" x-show="!photoPreview" src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://placehold.co/256x256/EFEFEF/333333?text=' . substr($user->name, 0, 1) }}" alt="{{ $user->name }}">
                    <div class="h-16 w-16 rounded-full bg-cover bg-no-repeat bg-center" x-show="photoPreview" :style="'background-image: url(\'' + photoPreview + '\');'"></div>
                </div>
                <div class="relative">
                    <input type="file" class="hidden" x-ref="photo" name="photo" @change="
                        photoName = $refs.photo.files[0].name;
                        const reader = new FileReader();
                        reader.onload = (e) => { photoPreview = e.target.result; };
                        reader.readAsDataURL($refs.photo.files[0]);
                    ">
                    <button type="button" class="bg-black text-white px-3 py-1 text-xs rounded-md font-semibold" x-on:click.prevent="$refs.photo.click()">
                        Edit
                    </button>
                </div>
            </div>
        </div>

        <!-- Full Name -->
        <div class="grid grid-cols-3 gap-6 items-center">
            <label for="name" class="text-sm font-medium text-gray-700">{{ __('Full Name') }}</label>
            <div class="col-span-2">
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </div>

        <!-- Username -->
        <div class="grid grid-cols-3 gap-6 items-center">
            <label for="username" class="text-sm font-medium text-gray-700">{{ __('Username') }}</label>
            <div class="col-span-2">
                <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('username')" />
            </div>
        </div>

        <!-- Email -->
        <div class="grid grid-cols-3 gap-6 items-center">
            <label for="email" class="text-sm font-medium text-gray-700">{{ __('Email') }}</label>
            <div class="col-span-2">
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="email" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
            <button type="submit" class="bg-black text-white px-6 py-2 rounded-md font-semibold hover:bg-gray-800 transition">{{ __('Save') }}</button>
        </div>
    </form>
</section>
