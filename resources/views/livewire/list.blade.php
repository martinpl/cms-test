<?php

use App\PostTypes\AnyPost;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

new class extends Livewire\Component
{
    use WithPagination;

    public $postType;

    #[Url(except: ['publish', 'draft'])]
    public $status = ['publish', 'draft'];

    #[Url]
    public $author;

    #[Url]
    public $search = '';

    #[Computed]
    public function posts()
    {
        return AnyPost::with('user')
            ->where('type', $this->postType['name'])
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

    #[Computed]
    private function counts()
    {
        return [
            'all' => AnyPost::whereType($this->postType['name'])
                ->whereStatus(['publish', 'draft'])
                ->count(),
            'mine' => AnyPost::whereType($this->postType['name'])
                ->whereStatus(['publish', 'draft'])
                ->whereUserId(auth()->id())
                ->count(),
            'publish' => AnyPost::whereType($this->postType['name'])->whereStatus('publish')->count(),
            'draft' => AnyPost::whereType($this->postType['name'])->whereStatus('draft')->count(),
            'trash' => AnyPost::whereType($this->postType['name'])->whereStatus('trash')->count(),
        ];
    }

    public function setStatus($status)
    {
        $this->author = null;
        $this->status = $status;
    }

    public function trash($postId)
    {
        AnyPost::find($postId)->trash();
    }

    public function restore($postId)
    {
        AnyPost::find($postId)->untrash();
    }

    public function destroy($postId)
    {
        AnyPost::destroy($postId);
    }
}; ?>

<div>
    <x-slot:action>
        <x-button :href="route('editor', $postType['name'])" size="xs">
            <x-icon name="circle-plus" class="text-black fill-white" />
            Create
        </x-button>
    </x-slot>
    {{-- TODO: Table api? --}}
    <div class="relative flex flex-col gap-4">
        <div class="flex justify-between">
            <x-tabs.list
                class="**:data-[slot=badge]:bg-muted-foreground/30 hidden **:data-[slot=badge]:size-5 **:data-[slot=badge]:rounded-full **:data-[slot=badge]:px-1 @4xl/main:flex">
                <x-tabs.trigger href="todo" wire:click.prevent="setStatus(['publish', 'draft'])" :active="$status == ['publish', 'draft']">
                    All
                    <x-badge variant="secondary">{{ $this->counts['all'] }}</x-badge>
                </x-tabs.trigger>
                @if ($this->counts['all'] != $this->counts['mine'])
                    <x-tabs.trigger href="todo" wire:click.prevent="$set('author', {{ auth()->id() }})">
                        Mine
                        <x-badge variant="secondary">{{ $this->counts['mine'] }}</x-badge>
                    </x-tabs.trigger>
                @endif
                <x-tabs.trigger href="todo" wire:click.prevent="setStatus('publish')" :active="$status == 'publish'">
                    Published
                    <x-badge variant="secondary">{{ $this->counts['publish'] }}</x-badge>
                </x-tabs.trigger>
                @if ($this->counts['draft'])
                    <x-tabs.trigger href="todo" wire:click.prevent="setStatus('draft')" :active="$status == 'draft'">
                        Draft
                        <x-badge variant="secondary">{{ $this->counts['draft'] }}</x-badge>
                    </x-tabs.trigger>
                @endif
                @if ($this->counts['trash'])
                    <x-tabs.trigger href="todo" wire:click.prevent="setStatus('trash')" :active="$status == 'trash'">
                        Trash
                        <x-badge variant="secondary">{{ $this->counts['trash'] }}</x-badge>
                    </x-tabs.trigger>
                @endif
            </x-tabs.list>
            <x-input type="search" wire:model.change="search" placeholder="Search..." class="max-w-64" />
        </div>
        <div class="overflow-hidden rounded-lg border">
            <x-table>
                <x-table.header class="bg-muted sticky top-0 z-10">
                    <x-table.row>
                        {{-- <x-table.head>
                        </x-table.head> --}}
                        {{-- <x-table.head>
                            <div class="flex items-center justify-center">
                                <x-checkbox />
                            </div>
                        </x-table.head> --}}
                        <x-table.head>
                            Title
                        </x-table.head>
                        <x-table.head>
                            Author
                        </x-table.head>
                        <x-table.head>
                            Date
                        </x-table.head>
                    </x-table.row>
                </x-table.header>
                <x-table.body class="**:data-[slot=table-cell]:first:w-8">
                    @foreach ($this->posts as $post)
                        <x-table.row class="group relative z-0 data-[dragging=true]:z-10 data-[dragging=true]:opacity-80">
                            {{-- <x-table.cell>
                                <x-button variant="ghost" size="icon" class="text-muted-foreground size-7 hover:bg-transparent">
                                    <x-icon name="grip-vertical" class="text-muted-foreground size-3" />
                                </x-button>
                            </x-table.cell> --}}
                            {{-- <x-table.cell class="align-baseline w-10">
                                <div class="mt-0.5 flex items-center justify-center">
                                    <x-checkbox />
                                </div>
                            </x-table.cell> --}}
                            <x-table.cell>
                                {{-- TODO: Add select --}}
                                <div>
                                    <x-button :href="route('editor', [$postType['name'], $post->id])" variant="link" class="text-foreground w-fit p-0 h-auto mb-1">
                                        {{ $post->title }}
                                    </x-button>
                                    @if ($post->status == 'draft')
                                        <span class="text-muted-foreground">
                                            — Draft
                                        </span>
                                    @endif
                                </div>
                                <div
                                    class="text-xs text-muted-foreground [&>a:hover]:text-primary flex gap-1 opacity-0 group-hover:opacity-100">
                                    @if ($status != 'trash')
                                        <a href="{{ route('editor', [$postType['name'], $post->id]) }}">
                                            Edit
                                        </a>
                                        |
                                        <button wire:click="trash({{ $post->id }})" class="text-destructive/80">
                                            Trash
                                        </button>
                                        |
                                        {{-- TODO --}}
                                        {{-- <button>Duplicate</button> --}}
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
                                        |
                                        <button wire:click="destroy({{ $post->id }})" class="text-destructive/80">
                                            Delete Permanently
                                        </button>
                                    @endif
                                </div>
                            </x-table.cell>
                            <x-table.cell>
                                {{ $post->user->name }}
                            </x-table.cell>
                            <x-table.cell>
                                {{ $post->created_at->format('Y/m/d \a\t H:i') }}
                            </x-table.cell>
                        </x-table.row>
                    @endforeach
                    @if ($this->posts->isEmpty())
                        <x-table.row>
                            <x-table.cell colspan="3" class="h-24 text-center">
                                No results.
                            </x-table.cell>
                        </x-table.row>
                    @endif
                </x-table.body>
            </x-table>
        </div>
        {{ $this->posts->links() }}
    </div>
</div>
