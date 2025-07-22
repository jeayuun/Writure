<x-guest-layout>
    <div class="grid md:grid-cols-2">
        <div class="flex flex-col justify-center items-center px-6 md:px-12 lg:px-24 py-12 min-h-screen">
            <div class="w-full max-w-md">
                <a href="/" class="text-4xl font-serif font-normal text-gray-800">Wrytte</a>

                <h2 class="text-3xl font-semibold text-gray-800 mt-10 mb-6">Sign In To Your Account</h2>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Email Address" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <x-text-input id="password" class="block mt-1 w-full"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password"
                                        placeholder="Password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                            Log In
                        </button>
                    </div>
                </form>

                <p class="mt-8 text-center text-sm text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-medium text-black hover:underline">
                        Sign up
                    </a>
                </p>
            </div>
        </div>
        <div class="hidden md:block" style="background-color: #303347;">
            <div class="flex items-center justify-center h-full">
                 <img class="max-w-full max-h-full object-contain" src="{{ asset('images/auth-image.png') }}" alt="Astronaut in space">
            </div>
        </div>
    </div>
</x-guest-layout>
