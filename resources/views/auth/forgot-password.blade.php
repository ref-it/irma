<x-guest-layout>
    <x-auth-card>
        <h2 class="font-bold text-zinc-800 dark:text-white mb-4">{{ __('Reset Password') }}</h2>
        <div class="mb-4 text-sm text-zinc-600 dark:text-zinc-300">
            {{ __('auth.forgot_password_text') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <!-- Email Address -->
            <x-input.group id="mail" name="mail" :label="__('Email')" :value="old('email')" required/>

            <div class="flex gap-x-6 items-center justify-end mt-5">
                <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 text-zinc-800 dark:text-white">{{  __('Cancel') }}</a>
                <x-button.primary type="submit">
                    {{ __('Send Reset Link') }}
                </x-button.primary>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
