<?php
 
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\PostTypes\Attachment;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Storage;
 
new class extends \Livewire\Volt\Component {
    use WithPagination;

    public $model;

    public $value;

    public $show = false;

    public function close($id) {
        // TODO: security (leaking post content)
        $this->value = $id;
    }

    #[Computed]
    public function posts() {
        return Attachment::latest()
            ->paginate(10);
    }
} ?>

<div>
    {{-- TODO: move to fields dir? --}}
    @if ($value)
        <img src="{{ Storage::url(Attachment::find($value)->content) }}" style="height: 100px">
    @endif
    <br>
    <button wire:click="$set('show', true)" type="button">
        Choose Image
    </button>
    @if ($show)
        <flux:modal wire:model.self="show" @close="close($wire.$parent.$get('{{ $model }}'))" >
            @foreach ($this->posts as $post)
                <div :class="{ 'border border-gray-800': $wire.$parent.$get('{{ $model }}') == {{ $post->id }}} ">
                    <button type="button" @click="$wire.$parent.$set('{{ $model }}', {{ $post->id }})">
                        <img src="{{ Storage::url($post->content) }}" style="height: 100px">
                    </button>
                </div>
            @endforeach
            {{ $this->posts->links() }} 
        </flux:modal>
    @endif
</div>