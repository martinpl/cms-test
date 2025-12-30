<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <x-sidebar.menu>
            <x-sidebar.menu-item>
                <x-sidebar.menu-button :href="route('settings.profile')" :is-active="request()->routeIs('settings.profile')" class="font-medium" wire:navigate>
                    {{ __('Profile') }}
                </x-sidebar.menu-button>
            </x-sidebar.menu-item>
            <x-sidebar.menu-item>
                <x-sidebar.menu-button :href="route('settings.password')" :is-active="request()->routeIs('settings.password')" class="font-medium" wire:navigate>
                    {{ __('Password') }}
                </x-sidebar.menu-button>
            </x-sidebar.menu-item>
            <x-sidebar.menu-item>
                <x-sidebar.menu-button :href="route('settings.appearance')" :is-active="request()->routeIs('settings.appearance')" class="font-medium" wire:navigate>
                    {{ __('Appearance') }}
                </x-sidebar.menu-button>
            </x-sidebar.menu-item>
        </x-sidebar.menu>
    </div>

    <x:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <div class="grid gap-1.5">
            <x-card.title>{{ $heading ?? '' }}</x-card.title>
            <x-card.description>{{ $subheading ?? '' }}</x-card.description>
        </div>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
