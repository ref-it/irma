<x-guest-layout>
    <x-auth-card>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <h2 class="font-bold text-zinc-800 dark:text-white">{{ __('Login') }}</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            @samlidp
            <!-- Email Address -->
            <x-input.group :label="__('Username or Mail')" class="block mt-1 w-full" name="uid" id="uid" :value="old('uid')" required autofocus />

            <!-- Password -->
            <x-input.group type="password" class="block mt-1 w-full" :label="__('Password')" id="password" name="password" required autocomplete="current-password"/>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-emerald-800 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-zinc-600 dark:text-zinc-300">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="link text-sm mr-auto" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button.primary class="ml-3" type="submit">
                    {{ __('Log in') }}
                </x-button.primary>
            </div>
        </form>
        <x-slot:footer class="text-sm space-x-1 text-center mt-8">
            <span class="text-zinc-500 dark:text-zinc-300">{{ __("Don't have an account?") }}</span>
            <a class="link" href="{{ route('register') }}">
                {{ __('Sign up and get started!') }}
            </a>
        </x-slot:footer>
    </x-auth-card>
</x-guest-layout>
