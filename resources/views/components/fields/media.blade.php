<?php

use App\PostTypes\Attachment;
use Illuminate\Support\Facades\Storage;

$modelHead = Str::before($model, '.');
$modelBody = Str::after($model, '.');
$selected = Arr::get($this->$modelHead, $modelBody);

?>
<div>
    {{-- TODO: security (leaking post content) --}}
    @if ($selected)
        <img src="{{ Storage::url(Attachment::find($selected)->content) }}" style="height: 100px">
    @endif
    <input type="hidden" wire:model="{{ $model }}" />
    <livewire:media-field-selector :$model :$selected :wire:key="$model" />
</div>