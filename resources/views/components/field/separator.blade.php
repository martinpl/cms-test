@props([
    'base' => "relative -my-2 h-5 text-sm group-data-[variant=outline]/field-group:-mb-2"
])

<div {{ $attributes->class($base) }}>
    <x-separator class="absolute inset-0 top-1/2" />
    @if ($slot->isNotEmpty())
        <span class="bg-background text-muted-foreground relative mx-auto block w-fit px-2">
            {{ $slot }}
        </span>
    @endif
</div>