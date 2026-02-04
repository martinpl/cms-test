<?php

use App\PostTypes\Attachment;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

new class extends Livewire\Component {
    use WithPagination;

    public $model;

    public $selected;

    public $show = false;

    #[Computed]
    public function posts()
    {
        return Attachment::latest()->paginate(10);
    }
}; ?>

<div>
    {{-- TODO: Should be render once not once per field --}}
    {{-- TODO: move to fields dir? --}}
    <button wire:click="$set('show', true)" type="button">
        Choose Image
    </button>
    @if ($show)
        <x-dialog>
            <x-dialog.content x-data="{ show: $wire.entangle('show').live }" @dialog-close="$wire.$parent.$set('{{ $model }}', $wire.selected)"
                class="sm:max-w-[425px]">
                @foreach ($this->posts as $post)
                    <div :class="{ 'border border-gray-800': $wire.selected == {{ $post->id }} }">
                        <button type="button" wire:click="selected = {{ $post->id }}">
                            <img src="{{ Storage::url($post->content) }}" style="height: 100px">
                        </button>
                    </div>
                @endforeach
                {{ $this->posts->links() }}
            </x-dialog.content>
        </x-dialog>
    @endif
    @if ($selected)
        <button wire:click="$wire.$parent.$set('{{ $model }}', null)">
            Remove
        </button>
    @endif
</div>
