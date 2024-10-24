<div>
    <div class="sm:flex-auto">
        <h1 class="text-base font-semibold leading-6 text-zinc-800 dark:text-white">{{ __('realms.dashboard.headline', ['name' => $name]) }}</h1>
        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">{{ __('realms.dashboard.explanation', ['name' => $name]) }}</p>
    </div>
    <div class="mt-10 grid sm:grid-cols-2 gap-4">
        <x-dashboard-card :href="route('profile')" color="bg-amber-400 text-zinc-800" icon="fas-user-gear">
            <x-slot:headline>
                {{ __('realms.dashboard.profile_heading', ['name' => $name]) }}
            </x-slot:headline>
            {{ __('realms.dashboard.profile_explanation', ['name' => $name]) }}
        </x-dashboard-card>

        <x-dashboard-card :href="route('committees.list', $uid)" color="bg-amber-400 text-zinc-800" icon="fas-sitemap">
            <x-slot:headline>
                {{ __('realms.dashboard.committee_headline', ['name' => $name]) }}
            </x-slot:headline>
            {{ __('realms.dashboard.committee_explanation', ['name' => $name]) }}
        </x-dashboard-card>

        <x-dashboard-card :href="route('realms.members', $uid)" color="bg-amber-400 text-zinc-800" icon="fas-users">
            <x-slot:headline>
                {{ __('realms.dashboard.members_heading', ['name' => $name]) }}
            </x-slot:headline>
            {{ __('realms.dashboard.members_explanation', ['name' => $name]) }}
        </x-dashboard-card>

        <x-dashboard-card :href="route('realms.mods', $uid)" color="bg-amber-400 text-zinc-800" icon="fas-graduation-cap">
            <x-slot:headline>
                {{ __('realms.dashboard.mods_headline', ['name' => $name]) }}
            </x-slot:headline>
            {{ __('realms.dashboard.mods_explanation', ['name' => $name]) }}
        </x-dashboard-card>

        <x-dashboard-card :href="route('realms.admins', $uid)" color="bg-amber-400 text-zinc-800" icon="fas-hat-wizard">
            <x-slot:headline>
                {{ __('realms.dashboard.admin_headline', ['name' => $name]) }}
            </x-slot:headline>
            {{ __('realms.dashboard.admin_explanation', ['name' => $name]) }}
        </x-dashboard-card>

        <x-dashboard-card :href="route('realms.edit', $uid)" color="bg-amber-400 text-zinc-800" icon="fas-wand-magic-sparkles"
                          :disabled="auth()->user()->cannot('edit', $community)">
            <x-slot:headline>
                {{ __('realms.dashboard.realms_edit_headline', ['name' => $name]) }}
            </x-slot:headline>
            {{ __('realms.dashboard.realms_edit_explanation', ['name' => $name]) }}
        </x-dashboard-card>

        <x-dashboard-card :href="route('realms.groups', $uid)" color="bg-amber-400 text-zinc-800" icon="fas-scroll"
                          :disabled="auth()->user()->cannot('viewAny', [\App\Ldap\Group::class, $community])">
            <x-slot:headline>
                {{ __('realms.dashboard.groups_headline', ['name' => $name]) }}
            </x-slot:headline>
            {{ __('realms.dashboard.groups_explanation', ['name' => $name]) }}
        </x-dashboard-card>

        <x-dashboard-card :href="route('realms.domains', $uid)" color="bg-amber-400 text-zinc-800" icon="fab-internet-explorer"
                          :disabled="auth()->user()->cannot('viewAny', [\App\Ldap\Domain::class, $community])">
            <x-slot:headline>
                {{ __('realms.dashboard.domains_headline', ['name' => $name]) }}
            </x-slot:headline>
            {{ __('realms.dashboard.domains_explanation', ['name' => $name]) }}
        </x-dashboard-card>

    </div>
</div>
