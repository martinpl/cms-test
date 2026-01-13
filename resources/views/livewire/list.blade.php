<?php

use App\PostTypes\AnyPost;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

// TODO: Duplicate action
new class extends \Livewire\Component
{
    use App\Livewire\Table;

    public $postType;

    public function views()
    {
        $views = [
            'all' => 'All',
            'mine' => 'Mine',
            'published' => 'Published',
            'draft' => 'Draft',
            'trash' => 'Trash',
        ];

        if ($this->counts['all'] == $this->counts['mine']) {
            unset($views['mine']);
        }

        return $views;
    }

    public function columns()
    {
        return [
            'title' => 'Title',
            'author' => 'Author',
            'date' => 'Date',
        ];
    }

    public function items()
    {
        $status = match ($this->view) {
            default => ['publish', 'draft'],
            'published' => ['publish'],
            'draft' => ['draft'],
            'trash' => ['trash'],
        };

        return AnyPost::with('user')
            ->where('type', $this->postType['name'])
            ->whereStatus($status)
            ->when($status == 'mine', function ($query) {
                $query->whereUserId(auth()->id());
            })
            ->when($this->search, function ($query) {
                $query->search($this->search);
            })
            ->latest()
            ->paginate(10);
    }

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
            'published' => AnyPost::whereType($this->postType['name'])->whereStatus('publish')->count(),
            'draft' => AnyPost::whereType($this->postType['name'])->whereStatus('draft')->count(),
            'trash' => AnyPost::whereType($this->postType['name'])->whereStatus('trash')->count(),
        ];
    }

    public function columnTitle($post)
    {
        $title = Blade::render(
            <<<'BLADE'
                <x-button
                    :href="route('editor', [$postType['name'], $post->id])"
                    variant="link"
                    class="text-foreground w-fit p-0 h-auto"
                >
                    {{ $post->title }}
                </x-button>
                @if ($post->status === 'draft')
                    <span class="text-muted-foreground">— Draft</span>
                @endif
                {{ $actions }}
            BLADE
            ,
            [
                'post' => $post,
                'postType' => $this->postType,
                'actions' => $this->actions($post),
            ],
        );

        // TODO: Direct return give error from ass: "Cannot use "::class" on int" / file parsing issue?
        $title = new HtmlString($title);

        return $title;
    }

    private function actions($post)
    {
        $actions = [];

        if ($this->view != 'trash') {
            // TODO: Not fan of this passing html
            $actions['edit'] = '<a href="'.route('editor', [$this->postType['name'], $post->id]).'">Edit</a>';
            $actions['trash'] = '<button wire:click="trash('.$post->id.')" class="text-destructive/80">Trash</button>';
            if ($post->link()) {
                $label = $post->status == 'draft' ? 'Preview' : 'View';
                $actions['view'] = '<a href="'.$post->link().'">'.$label.'</a>';
            }
        }

        if ($this->view == 'trash') {
            $actions['restore'] = '<button wire:click="restore('.$post->id.')">Restore</button>';
            $actions['delete'] = '<button wire:click="destroy('.$post->id.')" class="text-destructive/80">Delete Permanently</button>';
        }

        return $this->rowActions($actions);
    }

    public function columnAuthor($post)
    {
        return $post->user->name;
    }

    public function columnDate($post)
    {
        return $post->created_at->format('Y/m/d \a\t H:i');
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

<x-slot:action>
    <x-button :href="route('editor', $postType['name'])" size="xs">
        <x-icon name="circle-plus" class="text-black fill-white" />
        Create
    </x-button>
</x-slot>

{{ $this->table(search: true) }}
