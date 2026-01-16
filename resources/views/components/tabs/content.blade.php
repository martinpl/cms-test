@props(['value'])
@aware(['defaultValue'])

<div data-slot="tabs-content" {{ $attributes->twMerge('flex-1 outline-none') }}
    @isset($value) x-show="tab == '{{ $value }}'" @if ($defaultValue != $value) x-cloak @endif @endisset>
    {{ $slot }}
</div>
