@props([
    'base' => "flex flex-col gap-6
        has-[>[data-slot=checkbox-group]]:gap-3 has-[>[data-slot=radio-group]]:gap-3",
])

<fieldset {{ $attributes->class($base) }}>
    {{ $slot }}
</fieldset>