@aware(['defaultOpen'])

<div data-slot="collapsible-content" {{ $attributes }} x-show="collapsible" @if (!$defaultOpen) x-cloak @endif>
    {{ $slot }}
</div>
