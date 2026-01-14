@props([
    'side' => 'left',
    'variant' => 'sidebar',
    'collapsible' => 'offcanvas',
])

@if ($collapsible === 'none')
    <div data-slot="sidebar" {{ $attributes->twMerge('bg-sidebar text-sidebar-foreground flex h-full w-(--sidebar-width) flex-col') }}>
        {{ $slot }}
    </div>
@else
    <div data-slot="sidebar" class="group peer text-sidebar-foreground hidden md:block" {{-- data-state={state} --}} {{-- data-collapsible={state === "collapsed" ? collapsible : ""} --}}
        data-variant="{{ $variant }}" data-side="{{ $side }}">
        {{-- This is what handles the sidebar gap on desktop --}}
        <div data-slot="sidebar-gap" @class([
            'relative w-(--sidebar-width) bg-transparent transition-[width] duration-200 ease-linear',
            'group-data-[collapsible=offcanvas]:w-0',
            'group-data-[side=right]:rotate-180',
            $variant === 'floating' || $variant === 'inset'
                ? 'group-data-[collapsible=icon]:w-[calc(var(--sidebar-width-icon)+(--spacing(4)))]'
                : 'group-data-[collapsible=icon]:w-(--sidebar-width-icon)',
        ])></div>
        <div data-slot="sidebar-container"
            {{ $attributes->twMerge([
                'fixed inset-y-0 z-10 hidden h-svh w-(--sidebar-width) transition-[left,right,width] duration-200 ease-linear md:flex',
                $side === 'left'
                    ? 'left-0 group-data-[collapsible=offcanvas]:left-[calc(var(--sidebar-width)*-1)]'
                    : 'right-0 group-data-[collapsible=offcanvas]:right-[calc(var(--sidebar-width)*-1)]',
                // Adjust the padding for floating and inset variants.
                $variant === 'floating' || $variant === 'inset'
                    ? 'p-2 group-data-[collapsible=icon]:w-[calc(var(--sidebar-width-icon)+(--spacing(4))+2px)]'
                    : 'group-data-[collapsible=icon]:w-(--sidebar-width-icon) group-data-[side=left]:border-r group-data-[side=right]:border-l',
            ]) }}>
            <div data-sidebar="sidebar" data-slot="sidebar-inner"
                class="bg-sidebar group-data-[variant=floating]:border-sidebar-border flex h-full w-full flex-col group-data-[variant=floating]:rounded-lg group-data-[variant=floating]:border group-data-[variant=floating]:shadow-sm">
                {{ $slot }}
            </div>
        </div>
    </div>
@endif
