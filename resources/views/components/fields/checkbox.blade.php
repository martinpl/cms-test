<x-field tag="label" orientation="horizontal">
    <x-checkbox :wire:model="$getWireModel()" />
    <x-field.label tag="div" class="font-normal">
        {{ $title }}
    </x-field.label>
</x-field>