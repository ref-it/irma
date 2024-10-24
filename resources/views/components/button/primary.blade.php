<x-button
    {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-800 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-700']) }}
>
    {{ $slot }}
</x-button>
