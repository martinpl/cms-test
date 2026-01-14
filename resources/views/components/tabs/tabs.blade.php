@props(['defaultValue'])

<div data-slot="tabs" {{ $attributes->class('flex flex-col gap-2') }}
    @isset($defaultValue) x-data="{ tab: '{{ $defaultValue }}' }" @endisset>
    {{ $slot }}
</div>
