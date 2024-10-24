<div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
    <table class="min-w-full divide-y divide-cool-gray-200 dark:divide-zinc-800">
        <thead class="dark:bg-zinc-600 dark:border-b dark:border-zinc-800">
            <tr>
                {{ $head }}
            </tr>
        </thead>

        <tbody class="bg-white dark:bg-zinc-700 divide-y divide-cool-gray-200 dark:divide-zinc-800">
            {{ $slot }}
        </tbody>
    </table>
</div>
