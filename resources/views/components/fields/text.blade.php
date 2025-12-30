<x-field tag="label">
    <x-field.label tag="div">
        {{ $title }}
    </x-field.label>
    <x-input :wire:model="$getWireModel()" />
</x-field>