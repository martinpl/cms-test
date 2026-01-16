{{-- TODO: Add toggling / clear out component --}}
@php
    $SIDEBAR_WIDTH = '16rem';
    $SIDEBAR_WIDTH_ICON = '3rem';
@endphp

<div data-slot="sidebar-wrapper"
    {{ $attributes->twMerge('group/sidebar-wrapper has-data-[variant=inset]:bg-sidebar flex min-h-svh w-full')->style("--sidebar-width: {$SIDEBAR_WIDTH}; --sidebar-width-icon: {$SIDEBAR_WIDTH_ICON};") }}>
    {{ $slot }}
</div>
