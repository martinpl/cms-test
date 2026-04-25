<?php

use App\PostTypes\Attachment;
use Illuminate\Support\Facades\Storage;

$model = $self->model;
$modelHead = Str::before($model, '.');
$modelBody = Str::after($model, '.');
$sessionValue = Arr::get($this->$modelHead, $modelBody);
$selected = $sessionValue ?? $self->loadValue();

?>
<div>
    {{-- TODO: security (leaking post content) --}}
    @if ($selected)
        <img src="{{ Storage::url(Attachment::find($selected)?->content) }}" style="height: 100px">
    @endif
    <input type="hidden" {{ $attributes }} />
    <livewire:media-field-selector :$model :$selected :wire:key="$model" />
</div>
