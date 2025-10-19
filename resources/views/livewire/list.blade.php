<?php
 
use App\PostTypes\AnyPost;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
 
new class extends \Livewire\Volt\Component {
    use WithPagination;

    public $postType; 

    #[Computed]
    public function posts() {
        return AnyPost::where('type', $this->postType['name'])
            ->latest()
            ->paginate(10);
    }
} ?>

<div>
    <h2>
        {{ $postType['plural'] }}
    </h2>
    <a href="{{ route('editor', $postType['name']) }}">
        Add
    </a>
    @foreach ($this->posts as $post)
        <div>
            <a href="{{ route('editor', [$postType['name'], $post->id]) }}">
                {{ $post->title }}
            </a>
            @if ($post->link())
                <a href="{{ $post->link() }}">Preview</a>
            @endif
        </div>
        <hr>
    @endforeach
    {{ $this->posts->links() }} 
</div>