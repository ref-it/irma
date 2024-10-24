@props([
    'disabled' => false,
    'color',
    'icon',
    'headline',
    'href'
])

<div @class(["group relative bg-white dark:bg-zinc-700 dark:hover:bg-zinc-600 ring-1 ring-zinc-300 dark:ring-zinc-500 p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-emerald-700 shadow-sm rounded-lg",
        'hover:bg-gray-50' => !$disabled
    ])>
    <div>
      <span @class([
        "inline-flex rounded-lg p-4 border border-black/10",
        $color => !$disabled,
        "bg-gray-100 text-gray-400" => $disabled
      ])>
            <x-dynamic-component :component="$icon" class="h-5 w-5" />
      </span>
    </div>
    <div class="mt-8">
        <h3 @class(["text-base font-semibold leading-6",
                "text-zinc-800 dark:text-white" => !$disabled,
                "text-zinc-400 dark:text-zinc-500" => $disabled,
            ])>
            @if(!$disabled)
                <a href="{{ $href }}" class="focus:outline-none">
                    <!-- Extend touch target to entire panel -->
                    <span class="absolute inset-0" aria-hidden="true"></span>
                    {{ $headline }}
                </a>
            @else
                <span class="focus:outline-none cursor-not-allowed">
                    <span class="absolute inset-0" aria-hidden="true"></span>
                    {{ $headline }}
                </span>
            @endif

        </h3>
        <p @class(["mt-2 text-sm",
            "text-zinc-500 dark:text-zinc-300" => !$disabled,
            "text-zinc-400 dark:text-zinc-500" => $disabled,
        ])>
            {{ $slot }}
        </p>
    </div>
    <span @class(["pointer-events-none absolute right-6 top-6 ",
            "text-gray-300 group-hover:text-gray-400" => !$disabled,
            "text-gray-100" => $disabled
        ])
        aria-hidden="true">
        <x-fas-chevron-right class="h-10 w-10"/>
    </span>
</div>
