@aware(['name'])
@props([
    'showCloseButton' => true,
])

<div data-slot="dialog-portal" {{ $attributes->merge(['x-data' => '{ show: false }']) }} x-show="show" x-cloak
    @open-dialog.window="$event.detail == '{{ $name }}' && (show = true)"
    @close-dialog.window="$event.detail == '{{ $name }}' && (show = false)" @keyup.escape.window="show = false"
    x-effect="show == false && $dispatch('dialog-close')">
    <x-dialog.overlay @click="show = false" />
    <div data-slot="dialog-content" {{-- TODO: twMerge has some issue here --}}
        {{ $attributes->class('bg-background data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 fixed top-[50%] left-[50%] z-50 grid w-full max-w-[calc(100%-2rem)] translate-x-[-50%] translate-y-[-50%] gap-4 rounded-lg border p-6 shadow-lg duration-200 sm:max-w-lg') }}>
        {{ $slot }}
        @if ($showCloseButton)
            <button data-slot="dialog-close" type="button" @click="show = false"
                class="ring-offset-background focus:ring-ring data-[state=open]:bg-accent data-[state=open]:text-muted-foreground absolute top-4 right-4 rounded-xs opacity-70 transition-opacity hover:opacity-100 focus:ring-2 focus:ring-offset-2 focus:outline-hidden disabled:pointer-events-none [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4">
                <x-icon name="x" />
                <span class="sr-only">Close</span>
            </button>
        @endif
    </div>
</div>
