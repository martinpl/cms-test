<?php

new class extends Livewire\Component {
    public function setTheme($name)
    {
        set_option('theme', $name, true);
    }
}; ?>

<x-slot:title>
    {{ __('Themes') }}
</x-slot:title>

<div>
    @php($currentTheme = get_option('theme'))
    @foreach (\App\Theme::list() as $key => $theme)
        <div>
            {{ $theme['name'] }}<br>
            {{ $theme['version'] }}<br>
            {{ $theme['author'] }}<br>
            {{ $theme['description'] }}<br>
            @if ($currentTheme != $theme['slug'])
                <x-button wire:click="setTheme(`{{ $theme['slug'] }}`)">
                    {{ __('Active') }}
                </x-button>
            @endif
        </div>
    @endforeach
</div>
