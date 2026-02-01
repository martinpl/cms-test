@props([
    'tag' => 'div',
])

<x-tag :$tag
    {{ $attributes->twMerge('group/field-group @container/field-group flex w-full flex-col gap-7 data-[slot=checkbox-group]:gap-3 [&>[data-slot=field-group]]:gap-4') }}>
    {{ $slot }}
</x-tag>
