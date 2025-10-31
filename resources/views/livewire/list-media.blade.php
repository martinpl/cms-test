<?php
 
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\PostTypes\Attachment;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Storage;
 
new class extends Livewire\Component {
    use WithPagination;

    public $postType; 

    #[Computed]
    public function posts() {
        return Attachment::latest()
            ->paginate(10);
    }

    #[On('refreshList')]
    public function refresh() {}
} ?>

<div>
    <h2>
        {{ $postType['plural'] }}
    </h2>
    <livewire:list-media-upload >
    @foreach ($this->posts as $post)
        <div>
            <a href="{{ route('editor', [$postType['name'], $post->id]) }}">
                {{-- TODO: add helper --}}
                <img src="{{ Storage::url($post->content) }}" style="height: 100px">
            </a>
        </div>
        <hr>
    @endforeach
    {{ $this->posts->links() }} 
</div>