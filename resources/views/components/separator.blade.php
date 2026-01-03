@props([
    'orientation' => 'horizontal',
    'base' =>
        'bg-border shrink-0 data-[orientation=horizontal]:h-px data-[orientation=horizontal]:w-full data-[orientation=vertical]:h-full data-[orientation=vertical]:w-px',
])

<div data-slot="separator" data-orientation="{{ $orientation }}" {{ $attributes->class($base) }}></div>
