@props(['fields' => [], 'model' => false, 'live' => false])

<x-field.set>
    @foreach ($fields as $field)
        <x-field.group>
            @php
                // TODO: validation
                if ($model) {
                    $field->model("content.{$loop->parent->index}.data");
                }
                if ($live) {
                    $field->live();
                }
            @endphp
            {{ $field }}
        </x-field.group>
    @endforeach
</x-field.set>
