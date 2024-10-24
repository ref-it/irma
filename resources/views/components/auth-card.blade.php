<div class="flex h-full sm:justify-center items-center my-auto pt-6 sm:pt-0 space-y-3">
    <div class="my-auto">
        <div {{ $slot->attributes->merge(["class" => "w-screen sm:max-w-md p-6 overflow-hidden"]) }}>
            {{ $slot }}
        </div>
        @isset($footer->attributes)
            <div {{ $footer->attributes }}>
                {{ $footer ?? '' }}
            </div>
        @endif
    </div>
</div>

