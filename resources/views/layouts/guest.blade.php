<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('img/logo.svg') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @livewireStyles

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @livewireScripts
</head>
<body class="h-screen lg:grid-cols-1 lg:grid-rows-[4rem_1fr_3rem]">
    <div class="grow bg-zinc-50 dark:bg-zinc-800">
        <a class="flex h-16 px-6 shrink-0 items-center bg-zinc-800 border-b border-zinc-900 shadow-sm shadow-black/20" href="/">
            <img class="h-8 w-auto max-w-60" src="{{ asset('img/logo.svg') }}" alt="{{ config('app.name') }}">
        </a>
    </div>
    <main class="row-start-2">
        {{ $slot }}
    </main>
    @include('layouts.footer')
</body>
</html>
