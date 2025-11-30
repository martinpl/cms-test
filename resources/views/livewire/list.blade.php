<?php
 
use App\PostTypes\AnyPost;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
 
new class extends Livewire\Component {
    use WithPagination;

    public $postType; 

    #[Url(except: ['publish', 'draft'])]
    public $status = ['publish', 'draft']; 

    #[Url]
    public $author; 

    #[Url]
    public $search;

    #[Computed]
    public function posts() {
        return AnyPost::where('type', $this->postType['name'])
            ->whereStatus($this->status)
            ->when($this->author, function ($query) {
                $query->whereUserId($this->author);
            })
            ->when($this->search, function ($query) {
                $query->search($this->search);
            })
            ->latest()
            ->paginate(10);
    }

    public function setStatus($status) {
        $this->author = null;
        $this->status = $status;
    }

    public function trash($postId) {
        AnyPost::find($postId)->trash();
    }

    public function restore($postId) {
        AnyPost::find($postId)->untrash();
    }

    public function destroy($postId) 
    {
        AnyPost::destroy($postId);
    }
} ?>

<div>
    <div class="flex gap-2">
        <h2>
            {{ $postType['plural'] }}
        </h2>
        <a href="{{ route('editor', $postType['name']) }}">
            Add
        </a>
    </div>
    @php
        $allCount = AnyPost::whereType($this->postType['name'])->whereStatus(['publish', 'draft'])->count();
        $mineCount = AnyPost::whereType($this->postType['name'])->whereStatus(['publish', 'draft'])->whereUserId(auth()->id())->count();;
    @endphp
    <div>
        <a href="todo" wire:click.prevent="setStatus(['publish', 'draft']);">
            All ({{ $allCount }})
        </a> 
        @if ($allCount != $mineCount)
            <a href="todo" wire:click.prevent="$set('author', {{ auth()->id() }})">
                Mine ({{ $mineCount }})
            </a>
        @endif
        <a href="todo" wire:click.prevent="setStatus('publish')">
            Published  ({{ AnyPost::whereType($this->postType['name'])->whereStatus('publish')->count() }})
        </a>
        <a href="todo" wire:click.prevent="setStatus('draft')">
            Draft ({{ AnyPost::whereType($this->postType['name'])->whereStatus('draft')->count() }})
        </a> | 
        <a href="todo" wire:click.prevent="setStatus('trash')">
            Trash ({{ AnyPost::whereType($this->postType['name'])->whereStatus('trash')->count() }})
        </a>
        <input type="search" wire:model.change="search">
    </div>
    @foreach ($this->posts as $post)
        <div>
            {{-- TODO: Add select --}}
            <div>
                <a href="{{ route('editor', [$postType['name'], $post->id]) }}">
                    {{ $post->title }}
                </a>
                @if ($post->status == 'draft')
                    Draft
                @endif
            </div>
            <div class="flex gap-2">
                @if ($status != 'trash')
                    <a href="{{ route('editor', [$postType['name'], $post->id]) }}">
                        Edit
                    </a>
                    <button wire:click="trash({{ $post->id }})">
                        Trash
                    </button>
                    {{-- TODO --}}
                    {{-- <button>
                        Duplicate
                    </button> --}}
                    @if ($post->link())
                    {{-- TODO --}}
                        <a href="{{ $post->link() }}">
                            @if ($post->status == 'draft')
                                Preview
                            @else 
                                View
                            @endif
                        </a>
                    @endif
                @endif
                @if ($status == 'trash')
                    <button wire:click="restore({{ $post->id }})">
                        Restore
                    </button>
                    <button wire:click="destroy({{ $post->id }})">
                        Delete Permanently
                    </button>
                @endif
            </div>
        </div>
        <hr>
    @endforeach
    {{ $this->posts->links() }} 
</div>