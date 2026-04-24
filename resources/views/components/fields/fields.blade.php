@props(['fields' => [], 'model' => false, 'live' => false])

<x-field.set :$attributes>
    @foreach ($fields as $field)
        <x-field.group>
            @php
                // TODO: validation
                if ($model) {
                    // TODO: that should be passed / not hardcoded
                    $field->model("content.{$loop->parent->index}.data." . $field->name);
                }
            @endphp
            {{ $field->live($live) }}
        </x-field.group>
    @endforeach
</x-field.set>
