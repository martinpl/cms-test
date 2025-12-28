<?php

new class extends Livewire\Component {
    //
}; ?>

{{-- TODO: Prefix localStorage and js --}}
@include('partials.settings-heading')
<x-settings.layout :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">
    {{-- TODO: Could we have button / radio groups?  --}}
    <x-tabs.list class="h-10 w-full" x-data="{ appearance: $persist('system').as('appearance') }" x-effect="applyAppearance(appearance)">
        <x-tabs.trigger @click="appearance = 'light'" ::data-state="appearance == 'light' && 'active'" class="px-4 flex-1">
            <x-icon name="sun" />
            {{ __('Light') }}
        </x-tabs.trigger>
        <x-tabs.trigger @click="appearance = 'dark'" ::data-state="appearance == 'dark' && 'active'" class="px-4 flex-1">
            <x-icon name="moon" />
            {{ __('Dark') }}
        </x-tabs.trigger>
        <x-tabs.trigger @click="appearance = 'system'" ::data-state="appearance == 'system' && 'active'" class="px-4 flex-1">
            <x-icon name="monitor" />
            {{ __('System') }}
        </x-tabs.trigger>
    </x-tabs.list>
</x-settings.layout>
