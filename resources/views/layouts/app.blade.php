<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __($title ?? '') }}</title>
        <link rel="icon" href="{{ asset('img/logo.svg') }}">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        @livewireStyles
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        @livewireScripts
    </head>
    <body x-data="{ mobileMenu: false }">
        <x-navigation/>
        <div class="grid grid-rows-[4rem_1fr] h-[calc(100vh_-_3rem)] w-full">
            <div class="flex h-16 shrink-0 items-center gap-x-4 border-b border-zinc-900 bg-zinc-800 px-4 shadow-sm shadow-black/20 sm:px-6 lg:px-8 z-10">
                <button type="button" class="-m-2.5 p-2.5 text-zinc-700 lg:hidden" @click="mobileMenu = true">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path>
                    </svg>
                </button>
        
                <div class="ml-auto flex">
                    <!-- Help Icon -->
                    <a href="mailto:{{ config('app.help_contact_mail') }}" class="text-white hover:text-zinc-200 border-0">
                        <x-fas-question/>
                    </a>
                    <div>
                        <!-- Settings Dropdown -->
                        <div class="flex items-center ml-6 x-space-5">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center text-sm font-medium text-white hover:text-zinc-200 focus:outline-none focus:text-zinc-200 transition duration-150 ease-in-out">
                                        <div>{{ Auth::user()->full_name }}</div>

                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile')">{{ __('Profile') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('password.change')">{{ __('Change Password') }}</x-dropdown-link>

                                    <hr class="h-px my-1 mx-1 bg-zinc-200 dark:bg-zinc-600 border-0">

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <x-dropdown-link :href="route('logout')"
                                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-rows-[3.25rem_1fr] overflow-hidden">
                <!-- Page Heading -->
                <header class="border-b border-zinc-300 dark:border-zinc-900">
                    <div class="max-w-7xl mx-auto py-3.5 px-4 sm:px-6 lg:px-8">
                        <h2 class="font-semibold text-xl">
                            {{ Breadcrumbs::render(Route::current()->getName(), $routeParams)}}
                        </h2>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="h-full">
                    <div class="py-8">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <x-alert/>
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
        
        </div>

        <!-- Footer -->
        @include('layouts.footer')
    </body>
</html>
