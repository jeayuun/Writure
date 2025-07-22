<x-guest-layout>
    <div class="grid md:grid-cols-2">
        <!-- Image Section -->
        <div class="hidden md:block" style="background-color: #303347;">
            <div class="flex items-center justify-center h-full">
                 <img class="max-w-full max-h-full object-contain p-12" src="{{ asset('images/register-image.png') }}" alt="Person waving from a window">
            </div>
        </div>

        <!-- Form Section -->
        <div class="flex flex-col justify-center items-center px-6 md:px-12 lg:px-24 py-12 min-h-screen">
            <div class="w-full max-w-md">
                <a href="/" class="text-4xl font-serif font-normal text-gray-800">Writure</a>
                <h2 class="text-3xl font-semibold text-gray-800 mt-10 mb-6">Sign Up and Join Writure</h2>

                <form method="POST" action="{{ route('register') }}" id="register-form" class="space-y-6">
                    @csrf

                    <!-- Hidden name field for backend -->
                    <input type="hidden" name="name" id="name">

                    <!-- First and Last Name -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus placeholder="First Name" />
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required placeholder="Last Name" />
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />


                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Email Address" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                            Register
                        </button>
                    </div>
                </form>

                <p class="mt-8 text-center text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-black hover:underline">
                        Sign in
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // This script combines the first and last name fields into a single 'name' field
        // that the Laravel backend expects. This avoids having to change the backend code.
        const form = document.getElementById('register-form');
        const nameInput = document.getElementById('name');
        const firstNameInput = document.getElementById('first_name');
        const lastNameInput = document.getElementById('last_name');

        form.addEventListener('submit', function(e) {
            nameInput.value = `${firstNameInput.value} ${lastNameInput.value}`.trim();
        });
    </script>
</x-guest-layout>
