<?php
 
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\PostTypes\Attachment;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Storage;
 
new class extends Livewire\Component {
    use WithPagination;

    public $model;

    public $selected;

    public $show = false;

    #[Computed]
    public function posts() {
        return Attachment::latest()
            ->paginate(10);
    }
} ?>

<div>
    {{-- TODO: Should be render once not once per field --}}
    {{-- TODO: move to fields dir? --}}
    <button wire:click="$set('show', true)" type="button">
        Choose Image
    </button>
    @if ($show)
        <flux:modal wire:model.self="show" @close="$parent.$set('{{ $model }}', $wire.selected)" >
            @foreach ($this->posts as $post)
                <div :class="{ 'border border-gray-800': $wire.selected == {{ $post->id }} }">
                    <button type="button" wire:click="selected = {{ $post->id }}">
                        <img src="{{ Storage::url($post->content) }}" style="height: 100px">
                    </button>
                </div>
            @endforeach
            {{ $this->posts->links() }} 
        </flux:modal>
    @endif
</div>