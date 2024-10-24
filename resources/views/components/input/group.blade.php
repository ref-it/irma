@props([
    'help',
    'id' ,
    'error' => true,
    'note',
])
@php
    $model = $attributes->thatStartWith('wire:model')->first();
    if (empty($id)) $id = $model;
@endphp
<div class="mt-4 space-y-1">
    <div class="flex justify-between">
        @isset($label)
            {{-- Label can either be defined as slot --}}
            {{ $label }}
        @elseif($attributes->has('label'))
            {{-- or with the attributes like <x-input.group label="Upload" label.class="red" ...> --}}
            <label {{ $attributes->thatStartWith('label.')->merge(['class' => 'block text-sm font-medium leading-6 text-zinc-800 dark:text-white']) }}>
                {{ $attributes->get('label') }}
            </label>
        @endif
        @isset($note)
            <span class="text-sm leading-6 text-zinc-500 dark:text-zinc-300" id="{{ $id }}-note">{{ $note }}</span>
        @endisset
    </div>

    <input {{ $attributes->merge([
        'class' => 'block w-full rounded-md border-0 py-1.5 text-zinc-800 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-zinc-600
        disabled:cursor-not-allowed disabled:bg-zinc-50 dark:disabled:bg-zinc-700 disabled:text-zinc-500 dark:disabled:text-zinc-300 disabled:ring-zinc-200 dark:disabled:ring-zinc-600
        placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-emerald-800 sm:text-sm sm:leading-6',
        'type' => 'text',
        'disabled' => $attributes->has('disabled') // enforce disabled attribute being there (?)
    ]) }}/>

    @error($model) <small wire:key="{{ $model }}" class="block text-red-500">{{ $message }}</small> @enderror
    @isset($help) <small class="block text-xs text-gray-400">{{ $help }}</small> @endisset
    {{ $slot ?? '' }}
</div>
