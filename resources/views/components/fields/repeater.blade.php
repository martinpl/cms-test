<?php

$model = $getWireModel();
$modelHead = Str::before($model, '.');
$modelBody = Str::after($model, '.');
$items = Arr::get($this->$modelHead, $modelBody) ?? [];
$alpineModel = preg_replace('/\.(\d+)\./', '[$1].', $model); // TODO?

?>

<div>
    <div class="space-y-2">
        @foreach ($items as $item)
            <div class="border border-border rounded-lg bg-card transition-all" wire:key="{{ $model }}-{{ $loop->index }}">
                <div class="flex items-center gap-2 px-3 py-2 bg-muted/40 rounded-t-lg">
                    {{-- TODO --}}
                    {{-- <div class="cursor-grab active:cursor-grabbing text-muted-foreground hover:text-foreground">
                <x-icon name="grip-vertical" class="h-4 w-4" />
            </div> --}}
                    <button class="flex items-center gap-2 flex-1 text-left">
                        {{-- <span class="text-muted-foreground">
                    <x-icon name="chevron-up" class="h-4 w-4" />
                    <x-icon name="chevron-down" class="h-4 w-4" />
                </span>
                <span class="text-sm font-medium text-foreground">
                    {{ $title }}
                </span> --}}
                    </button>
                    <div class="flex items-center gap-1">
                        {{-- <x-button variant="ghost" size="icon" class="h-7 w-7 text-muted-foreground hover:text-foreground" title="Duplicate row">
                    <x-icon name="copy" />
                </x-button> --}}
                        <x-button variant="ghost" size="icon" class="h-7 w-7 text-muted-foreground hover:text-destructive"
                            @click="$wire.{{ $alpineModel }}.splice({{ $loop->index }}, 1); $wire.$refresh()" title="Remove row">
                            <x-icon name="trash-2" />
                        </x-button>
                    </div>
                </div>
                <div class="p-4 md:p-6 space-y-4">
                    @foreach ($getSchema() as $field)
                        {{ $field->live($getLive())->model("{$model}.{$loop->parent->index}") }}
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-3 flex items-center justify-between">
        <x-button type="button" variant="outline" size="sm" class="gap-1.5 bg-transparent"
            wire:click="$set('{{ $model }}.{{ count($items) }}', [])">
            <x-icon name="plus" class="h-4 w-4" />
            Add row
        </x-button>
    </div>
</div>
