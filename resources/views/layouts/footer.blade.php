<footer class="col-span-2 flex flex-col sm:flex-row items-center justify-between px-6 py-3 border-t border-zinc-300 dark:border-zinc-900 text-sm dark:text-white">
    <div class="flex space-x-1 items-center">
        <span>{{ __('code with') }}</span>
        <x-fas-heart class="text-red-600"/>
        <span>{{ __('by') }}</span>
        <a href="https://open-administration.de/" target="_blank" rel="noopener noreferrer">Open Administration</a>
        <span>{{ __('and') }}</span>
        <a href="https://stura.eu/it" target="_blank" rel="noopener noreferrer">Ref IT</a>
    </div>
    <div class="flex items-center space-x-5">
        <a href="{{ config('app.about_url') }}" target="_blank" rel="noopener noreferrer">{{ __('About') }}</a>
        <a href="{{ config('app.terms_url') }}" target="_blank" rel="noopener noreferrer">{{ __('Terms') }}</a>
        <a href="{{ config('app.privacy_url') }}" target="_blank" rel="noopener noreferrer">{{ __('Privacy') }}</a>
    </div>
</footer>
