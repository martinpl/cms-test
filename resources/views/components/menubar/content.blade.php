@php

$base = "bg-popover text-popover-foreground data-[state=open]:animate-in data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2 z-50 min-w-[12rem] origin-(--radix-menubar-content-transform-origin) overflow-hidden rounded-md border p-1 shadow-md";
$hover = "group-hover:animate-in group-hover:fade-in-0 group-hover:zoom-in-95";

@endphp

<div class="hidden group-hover:block" style="
    padding-top: 0.5rem;
    position: absolute; 
    position-anchor: --menubar; 
    position-area: block-end span-inline-end;
    position-try-fallbacks: block-end span-inline-start;
    z-index: 1001;
">
    <div {{ $attributes->class("{$base} {$hover}") }}>
        {{ $slot }}
    </div>
</div>