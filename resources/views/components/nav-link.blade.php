@props(['active'])

@php
$classes = ($active ?? false)
            ? 'bg-emerald-800 text-white group flex gap-x-3 rounded-md p-2 leading-6 font-semibold'
            : 'hover:bg-zinc-200 dark:hover:bg-zinc-700 dark:text-white group flex gap-x-3 rounded-md p-2 leading-6 font-semibold';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
