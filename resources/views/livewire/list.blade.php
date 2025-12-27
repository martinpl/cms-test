<?php

use App\PostTypes\AnyPost;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

new class extends Livewire\Component {
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
    @php
        $allCount = AnyPost::whereType($this->postType['name'])
            ->whereStatus(['publish', 'draft'])
            ->count();
        $mineCount = AnyPost::whereType($this->postType['name'])
            ->whereStatus(['publish', 'draft'])
            ->whereUserId(auth()->id())
            ->count();
    @endphp
    {{-- TODO: Table api? --}}
    <div class="relative flex flex-col gap-4">
        <div class="flex justify-between">
            <x-tabs.list
                class="**:data-[slot=badge]:bg-muted-foreground/30 hidden **:data-[slot=badge]:size-5 **:data-[slot=badge]:rounded-full **:data-[slot=badge]:px-1 @4xl/main:flex">
                <x-tabs.trigger href="todo" wire:click.prevent="setStatus(['publish', 'draft'])" :active="$status == ['publish', 'draft']">
                    All
                    <x-badge variant="secondary">{{ $allCount }}</x-badge>
                </x-tabs.trigger>
                @if ($allCount != $mineCount)
                    <x-tabs.trigger href="todo" wire:click.prevent="$set('author', {{ auth()->id() }})">
                        Mine
                        <x-badge variant="secondary">{{ $mineCount }}</x-badge>
                    </x-tabs.trigger>
                @endif
                <x-tabs.trigger href="todo" wire:click.prevent="setStatus('publish')" :active="$status == 'publish'">
                    Published
                    <x-badge
                        variant="secondary">{{ AnyPost::whereType($this->postType['name'])->whereStatus('publish')->count() }}</x-badge>
                </x-tabs.trigger>
                <x-tabs.trigger href="todo" wire:click.prevent="setStatus('draft')" :active="$status == 'draft'">
                    Draft
                    <x-badge variant="secondary">{{ AnyPost::whereType($this->postType['name'])->whereStatus('draft')->count() }}</x-badge>
                </x-tabs.trigger>
                <x-tabs.trigger href="todo" wire:click.prevent="setStatus('trash')" :active="$status == 'trash'">
                    Trash
                    <x-badge variant="secondary">{{ AnyPost::whereType($this->postType['name'])->whereStatus('trash')->count() }}</x-badge>
                </x-tabs.trigger>
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
        {{-- <div class="flex items-center justify-between px-4">
            <div class="text-muted-foreground hidden flex-1 text-sm lg:flex">
                0 of 68 row(s) selected.
            </div>
            <div class="flex w-full items-center gap-8 lg:w-fit">
                <div class="hidden items-center gap-2 lg:flex">
                    <x-label htmlFor="rows-per-page" class="text-sm font-medium">
                        Rows per page
                    </x-label>
                    <x-select>
                        <x-select.trigger size="sm" class="w-20" id="rows-per-page">
                            <x-select.value placeholder={table.getState().pagination.pageSize} />
                        </x-select.trigger>
                        <x-select.content side="top">
                            <x-select.item key={pageSize} value={`${pageSize}`}>
                                {pageSize}
                            </x-select.item>
                        </x-select.content>
                    </x-select>
                </div>
                <div class="flex w-fit items-center justify-center text-sm font-medium">
                    Page {{ $this->posts->currentPage() }} of {{ $this->posts->lastPage() }}
                </div>
                <div class="ml-auto flex items-center gap-2 lg:ml-0">
                    <x-button variant="outline" class="hidden h-8 w-8 p-0 lg:flex">
                        <span class="sr-only">Go to first page</span>
                        <x-icon name="chevrons-left" />
                    </x-button>
                    <x-button variant="outline" class="size-8" size="icon">
                        <span class="sr-only">Go to previous page</span>
                        <x-icon name="chevron-left" />
                    </x-button>
                    <x-button variant="outline" class="size-8" size="icon">
                        <span class="sr-only">Go to next page</span>
                        <x-icon name="chevron-right" />
                    </x-button>
                    <x-button variant="outline" class="hidden size-8 lg:flex" size="icon">
                        <span class="sr-only">Go to last page</span>
                        <x-icon name="chevrons-right" />
                    </x-button>
                </div>
            </div>
        </div> --}}
    </div>
    {{ $this->posts->links() }}
</div>
