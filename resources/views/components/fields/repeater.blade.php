<?php

$model = $getWireModel();
$modelHead = Str::before($model, '.');
$modelBody = Str::after($model, '.');
$items = Arr::get($this->$modelHead, $modelBody) ?? [];
$alpineModel = preg_replace('/\.(\d+)\./', '[$1].', $model); // TODO?

?>

<div>
    {{ $title }}
    @foreach($items as $item)
        <div wire:key="{{ $model }}-{{ $loop->index }}" style="border: 1px solid #ccc; padding: 10px; margin: 10px 0;">
            @foreach ($getSchema() as $field)
                {{ $field->model("{$model}.{$loop->parent->index}") }}
            @endforeach
            <x-button variant="outline" @click="$wire.{{ $alpineModel }}.splice({{ $loop->index }}, 1); $wire.$refresh()" type="button">-</x-button>
        </div>
    @endforeach
    <x-button variant="outline"  wire:click="$set('{{ $model }}.{{ count($items) }}', [])">
        Add row
    </x-button>
</div>