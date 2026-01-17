@props(['defaultOpen' => false])

<div data-slot="collapsible" {{ $attributes }} x-data="{ collapsible: {{ $defaultOpen ? 'true' : 'false' }} }" :data-state="collapsible ? 'open' : 'closed'">
    {{ $slot }}
</div>
