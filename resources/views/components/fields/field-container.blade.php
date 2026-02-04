<x-field tag="label">
    <x-field.label tag="div">
        {{ $field->title }}
    </x-field.label>
    {!! Blade::renderComponent($field) !!}
</x-field>
