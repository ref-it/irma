<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' => 'text-emerald-800 enabled:hover:text-emerald-900 disabled:opacity-25 disabled:cursor-not-allowed',
    ]) }}
>
    {{ $slot }}
</button>
