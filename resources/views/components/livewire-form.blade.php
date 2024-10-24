<form {{ $attributes->merge(['wire:submit' => 'save']) }}>
    {{ $slot }}
    <div class="mt-6 flex items-center justify-end gap-x-2">
        @isset($abort_route)
            <a wire:navigate href="{{ $abort_route }}" class="rounded-md bg-white dark:bg-zinc-700 px-3 py-2 text-sm font-semibold text-zinc-800 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-500 hover:bg-zinc-50 dark:hover:bg-zinc-600">{{  __('Cancel') }}</a>
        @else
            <a wire:navigate href="{{ url()->previous() }}" class="rounded-md bg-white dark:bg-zinc-700 px-3 py-2 text-sm font-semibold text-zinc-800 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-500 hover:bg-zinc-50 dark:hover:bg-zinc-600">{{  __('Cancel') }}</a>
        @endisset
        <button type="submit" class="rounded-md bg-emerald-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-800">
            {{ __('Save') }}
        </button>
    </div>
</form>
