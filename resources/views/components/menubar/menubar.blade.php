<!--
    https://ui.shadcn.com/docs/components/menubar
    https://github.com/shadcn-ui/ui/blob/main/apps/v4/registry/new-york-v4/ui/menubar.tsx

    TODO: Hover should not be default way to open menu
-->

@php
    
$base = "bg-background flex h-9 items-center gap-1 rounded-md border p-1 shadow-xs";

@endphp

<div {{ $attributes->class($base) }}>
    {{ $slot }}
</div>
