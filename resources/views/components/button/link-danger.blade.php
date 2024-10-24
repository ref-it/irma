<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' => 'flex gap-x-1.5 items-center px-2 py-1 -my-1 rounded-md shadow-sm text-white bg-red-600 enabled:hover:bg-red-500 disabled:opacity-25 disabled:cursor-not-allowed',
    ]) }}
>
    @if($attributes->has('icon-leading'))
        <x-dynamic-component class="w-4 h-4" :component="$attributes->get('icon-leading')"/>
    @endif
    <span>{{ $slot }}</span>
    @if($attributes->has('icon-trailing'))
        <x-dynamic-component :component="$attributes->get('icon-trailing')" class="w-4 h-4"/>
    @endif
</button>
