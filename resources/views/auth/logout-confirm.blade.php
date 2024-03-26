<x-guest-layout>
    <x-auth-card>
        <x-slot:slot class="space-y-5">
            <h2 class="font-bold text-gray-900 sm:truncate sm:tracking-tight">{{ __('Confirm logout') }}</h2>
            <p>
                {{ __('auth.logout_confirmation', ['user' => $shown_username]) }}
            </p>
            <form method="POST" action="{{ route('logout', ['redirect_uri' => $redirect_uri]) }}">
                @csrf
                <div class="flex justify-evenly">
                    <x-button.link href="/">{{ __('Cancel') }}</x-button.link>
                    <x-button.primary type="submit">{{ __('Log Out') }}</x-button.primary>
                </div>
            </form>
        </x-slot:slot>
        <!-- Validation Errors -->
    </x-auth-card>
</x-guest-layout>
