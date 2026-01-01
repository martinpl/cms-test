@props([
    'base' =>
        "focus:bg-accent focus:text-accent-foreground [&_svg:not([class*='text-'])]:text-muted-foreground relative flex w-full cursor-default items-center gap-2 rounded-sm py-1.5 pr-8 pl-2 text-sm outline-hidden select-none data-[disabled]:pointer-events-none data-[disabled]:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4 *:[span]:last:flex *:[span]:last:items-center *:[span]:last:gap-2",
    'hover' => 'hover:bg-accent hover:text-accent-foreground',
])

<div data-slot="select-item" {{ $attributes->class([$base, $hover]) }}
    onclick="
        const root = this.closest('[data-slot=select]');
        const select = root.querySelector('select');
        select.value = '{{ $slot }}'; 
        select.dispatchEvent(new Event('change'));
        this.parentElement.parentElement.hidePopover();

        root.querySelectorAll('[data-slot=select-item-check]').forEach(el => el.hidden = true);
        this.querySelector('[data-slot=select-item-check]').hidden = false;
">
    {{ $slot }}
    <span data-slot="select-item-check" class="absolute right-2 flex size-3.5 items-center justify-center" hidden>
        <x-icon name="check" class="size-4" />
    </span>
</div>

@push('select')
    <option id="{{ $slot }}">
        {{ $slot }}
    </option>
@endpush
