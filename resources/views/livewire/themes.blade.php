<?php
 
use Livewire\Volt\Component;
 
new class extends Component {
    public function setTheme($name)
    {
        set_option('theme', $name, true);
    }
} ?>
<div>
    @php($currentTheme = get_option('theme'))
    @foreach (\App\Theme::list() as $key => $theme)
        <div>
            {{ $theme['name'] }}<br>
            {{ $theme['version'] }}<br>
            {{ $theme['author'] }}<br>
            {{ $theme['description'] }}<br>
            @if ($currentTheme != $theme['slug'])
                <flux:button wire:click="setTheme(`{{ $theme['slug'] }}`)">
                    {{ __('Active') }}
                </flux:button>
            @endif
        </div>
    @endforeach
</div>