{{--
-- Important note:
--
-- This template is based on an example from Tailwind UI, and is used here with permission from Tailwind Labs
-- for educational purposes only. Please do not use this template in your own projects without purchasing a
-- Tailwind UI license, or they’ll have to tighten up the licensing and you’ll ruin the fun for everyone.
--
-- Purchase here: https://tailwindui.com/
--}}

<tr {{ $attributes->merge(['class' => 'bg-white dark:bg-zinc-700 group hover:bg-gray-50 dark:hover:bg-zinc-600 dark:text-white']) }}>
    {{ $slot }}
</tr>
