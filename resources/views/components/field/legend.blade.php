@props([
    'variant' => 'legend',
    'base' => "mb-3 font-medium
        data-[variant=legend]:text-base
        data-[variant=label]:text-sm",
])

<legend data-variant="{{ $variant }}" {{ $attributes->class($base) }}>
    {{ $slot }}
</legend>