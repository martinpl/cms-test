{{-- TODO: Add toggling / clear out component --}}
@php
    $SIDEBAR_WIDTH = '16rem';
    $SIDEBAR_WIDTH_ICON = '3rem';
@endphp

<div data-slot="sidebar-wrapper" style="
    --sidebar-width: {{ $SIDEBAR_WIDTH }}; 
    --sidebar-width-icon: {{ $SIDEBAR_WIDTH_ICON }}"
    class="group/sidebar-wrapper has-data-[variant=inset]:bg-sidebar flex min-h-svh w-full">
    {{ $slot }}
</div>
