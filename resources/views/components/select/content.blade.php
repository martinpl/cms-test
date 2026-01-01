@props([
    'base' =>
        'bg-popover text-popover-foreground data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2 relative z-50 max-h-(--radix-select-content-available-height) min-w-[8rem] origin-(--radix-select-content-transform-origin) overflow-x-hidden overflow-y-auto rounded-md border shadow-md',
    'position' => 'popper',
])

<style>
    :root {
        --radix-select-trigger-height: 10rem;
    }
</style>
<div data-slot="select-content"
    {{ $attributes->class([$base, 'data-[side=bottom]:translate-y-1 data-[side=left]:-translate-x-1 data-[side=right]:translate-x-1 data-[side=top]:-translate-y-1' => $position === 'popper']) }}
    popover
    style="
        position-anchor: --select; 
        position-area: block-end span-inline-end;
        position-try-fallbacks: block-end span-inline-start;
        width: anchor-size(width);
">
    <div @class([
        'p-1',
        'h-[var(--radix-select-trigger-height)] w-full min-w-[var(--radix-select-trigger-width)] scroll-my-1' =>
            $position == 'popper',
    ])>
        {{ $slot }}
    </div>
</div>
