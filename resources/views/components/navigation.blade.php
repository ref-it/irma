<!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
<div x-show="mobileMenu" class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
    <div x-show="mobileMenu"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-zinc-900/80"></div>
<div class="fixed inset-0 flex">
    <div x-show="mobileMenu"
    x-transition:enter="transition ease-in-out duration-300 transform"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in-out duration-300 transform"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="relative mr-16 flex w-full max-w-xs flex-1"
    @click.outside="mobileMenu = false">
    <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
        <button type="button" class="-m-2.5 p-2.5" @click="mobileMenu = false">
            <span class="sr-only">Close sidebar</span>
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
                
    <div class="grid grid-rows-[auto_1fr] h-screen grow bg-zinc-50 dark:bg-zinc-800">
        <a class="flex h-16 px-6 shrink-0 items-center bg-zinc-800 border-b border-zinc-900 shadow-sm shadow-black/20" href="/infos?election=1">
            <img class="h-8 w-auto max-w-60" src="{{ asset('img/logo.svg') }}" alt="{{ config('app.name') }}">
        </a>
        <nav class="flex flex-1 flex-col px-6 py-4 h-full overflow-y-auto border-r border-zinc-300 dark:border-zinc-900">
                <ul role="list" class="flex flex-1 flex-col gap-y-7">
                    <li>
                        <ul role="list" class="-mx-2 space-y-1">
                            <li>
                                <a wire:navigate
                                    class="hover:bg-zinc-200 dark:hover:bg-zinc-700 dark:text-white group flex gap-x-3 rounded-md p-2 leading-6 font-semibold"
                                    :active="Route::is('realms.dashboard')"
                                    href="/"
                                >
                                    <x-fas-house class="h-6 w-6 shrink-0" /> {{ __('Dashboard') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                    @can('picked', \App\Ldap\Community::class)
                        <li>
                            <div class="mb-2 text-sm font-semibold leading-6 text-zinc-700 dark:text-zinc-400">Admin-Funktionen</div>
                            <ul role="list" class="-mx-2 space-y-1">
                                <li>
                                    <x-nav-link wire:navigate :active="request()->routeIs('realms.members')"
                                                :href="route('realms.members', ['uid' => $uid])"
                                    >
                                        <x-fas-users class="h-6 w-6 shrink-0" /> {{ __('People') }}
                                    </x-nav-link>
                                </li>
                                <li>
                                    <x-nav-link wire:navigate :active="request()->routeIs('committees.list')"
                                                :href="route('committees.list', ['uid' => $uid])"
                                    >
                                        <x-fas-sitemap class="h-6 w-6 shrink-0" /> {{ __('Committees and Roles') }}
                                    </x-nav-link>
                                </li>
                                <li>
                                    <x-nav-link wire:navigate :active="request()->routeIs('realms.groups')"
                                                :href="route('realms.groups', ['uid' => $uid])"
                                    >
                                        <x-fas-key class="h-6 w-6 shrink-0" /> {{ __('realms.dashboard.groups_headline') }}
                                    </x-nav-link>
                                </li>
                            </ul>
                        </li>
                    @endcan
                    @can('superadmin', \App\Models\User::class)
                        <li>
                            <div class="mb-2 text-sm font-semibold leading-6 text-zinc-700 dark:text-zinc-400">Superadmin-Funktionen</div>
                            <ul role="list" class="-mx-2 space-y-1">
                                <li>
                                    <x-nav-link wire:navigate :active="request()->routeIs('superadmins.list')"
                                                :href="route('superadmins.list')"
                                    >
                                        <x-fas-dragon class="h-6 w-6 shrink-0" /> {{ __('Superadmins') }}
                                    </x-nav-link>
                                </li>
                                <li>
                                    <x-nav-link :active="request()->routeIs('realms.pick')"
                                                :href="route('realms.pick')">
                                        <x-fas-repeat class="h-6 w-6 shrink-0" /> {{ __('Change Realm') }}
                                    </x-nav-link>
                                </li>
                            </ul>
                        </li>
                    @endcan
                </ul>
            </nav>
        </div>
    </div>
</div>
</div>
    
<!-- Static sidebar for desktop -->
<div class="hidden lg:inline">
    <div class="grid grid-rows-[auto_1fr] h-full grow bg-zinc-50 dark:bg-zinc-800">
        <a class="flex h-16 px-6 shrink-0 items-center bg-zinc-800 border-b border-zinc-900 shadow-sm shadow-black/20" href="/">
            <img class="h-8 w-auto max-w-60" src="{{ asset('img/logo.svg') }}" alt="{{ config('app.name') }}">
        </a>
        <nav class="flex flex-1 flex-col px-6 py-4 h-full overflow-y-auto border-r border-zinc-300 dark:border-zinc-900">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                    <ul role="list" class="-mx-2 space-y-1">
                        <li>
                            <a wire:navigate
                                class="hover:bg-zinc-200 dark:hover:bg-zinc-700 dark:text-white group flex gap-x-3 rounded-md p-2 leading-6 font-semibold"
                                :active="Route::is('realms.dashboard')"
                                href="/"
                            >
                                <x-fas-house class="h-6 w-6 shrink-0" /> {{ __('Dashboard') }}
                            </a>
                        </li>
                    </ul>
                </li>
                @can('picked', \App\Ldap\Community::class)
                    <li>
                        <div class="mb-2 text-sm font-semibold leading-6 text-zinc-700 dark:text-zinc-400">Admin-Funktionen</div>
                        <ul role="list" class="-mx-2 space-y-1">
                            <li>
                                <x-nav-link wire:navigate :active="request()->routeIs('realms.members')"
                                            :href="route('realms.members', ['uid' => $uid])"
                                >
                                    <x-fas-users class="h-6 w-6 shrink-0" /> {{ __('People') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link wire:navigate :active="request()->routeIs('committees.list')"
                                            :href="route('committees.list', ['uid' => $uid])"
                                >
                                    <x-fas-sitemap class="h-6 w-6 shrink-0" /> {{ __('Committees and Roles') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link wire:navigate :active="request()->routeIs('realms.groups')"
                                            :href="route('realms.groups', ['uid' => $uid])"
                                >
                                    <x-fas-key class="h-6 w-6 shrink-0" /> {{ __('realms.dashboard.groups_headline') }}
                                </x-nav-link>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('superadmin', \App\Models\User::class)
                    <li>
                        <div class="mb-2 text-sm font-semibold leading-6 text-zinc-700 dark:text-zinc-400">Superadmin-Funktionen</div>
                        <ul role="list" class="-mx-2 space-y-1">
                            <li>
                                <x-nav-link wire:navigate :active="request()->routeIs('superadmins.list')"
                                            :href="route('superadmins.list')"
                                >
                                    <x-fas-dragon class="h-6 w-6 shrink-0" /> {{ __('Superadmins') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :active="request()->routeIs('realms.pick')"
                                            :href="route('realms.pick')">
                                    <x-fas-repeat class="h-6 w-6 shrink-0" /> {{ __('Change Realm') }}
                                </x-nav-link>
                            </li>
                        </ul>
                    </li>
                @endcan
            </ul>
        </nav>
    </div>
</div>
    
    
