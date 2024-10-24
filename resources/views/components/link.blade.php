@unless($attributes->get('disabled'))
    <a {{ $attributes->merge([
        'class' => 'flex gap-x-1.5 items-center text-emerald-800 dark:text-amber-400 hover:text-emerald-900 dark:hover:text-amber-300',
    ]) }}>
        {{ $slot }}
    </a>
@else
    <span {{ $attributes->except(['wire:click', 'href'])->merge([
        'class' => 'opacity-25 cursor-not-allowed flex gap-x-1.5 items-center text-emerald-800'
    ])}}>
        {{ $slot }}
    </span>
@endunless
