@props(['inset' => false])

<div data-slot="menubar-sub-trigger" data-inset="{{ $inset }}"
    class="focus:bg-accent focus:text-accent-foreground data-[state=open]:bg-accent data-[state=open]:text-accent-foreground flex cursor-default items-center rounded-sm px-2 py-1.5 text-sm outline-none select-none data-[inset]:pl-8">
    {{ $slot }}
    <x-icon name="chevron-right" class="ml-auto h-4 w-4" />
</div>
