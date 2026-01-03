<div data-slot="menubar-radio-item"
    class="focus:bg-accent focus:text-accent-foreground relative flex cursor-default items-center gap-2 rounded-xs py-1.5 pr-2 pl-8 text-sm outline-hidden select-none data-[disabled]:pointer-events-none data-[disabled]:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4">
    <span class="pointer-events-none absolute left-2 flex size-3.5 items-center justify-center">
        {{-- <MenubarPrimitive.ItemIndicator> --}}
        <x-icon name="circle" class="size-2 fill-current" />
        {{-- </MenubarPrimitive.ItemIndicator> --}}
    </span>
    {{ $slot }}
</div>
