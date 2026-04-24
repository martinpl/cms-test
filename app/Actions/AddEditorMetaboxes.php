<?php

namespace App\Actions;

use App\Facades\Metabox;
use App\View\Components\Fields\Media;
use App\View\Components\Fields\Number;
use App\View\Components\Fields\Text;
use App\View\Components\Fields\Textarea;

class AddEditorMetaboxes
{
    public function __invoke()
    {
        Metabox::make()
            ->id('home')
            ->location('editor.side')
            ->when(fn ($post) => $post->supports('home'))
            ->blade(<<<'BLADE'
                @if ($this->post?->link())
                    <x-button class="w-full" variant="outline" size="sm" wire:click="setAsHomePage({{ $this->post->id }})">
                        Set as homepage
                    </x-button>
                @endif
            BLADE)
            ->with('seamless', true)
            ->priority(1)
            ->register();

        Metabox::make()
            ->id('featured-image')
            ->location('editor.side')
            ->when(fn ($post) => $post->supports('thumbnail'))
            ->fields([
                Media::make('Featured image')
                    ->model('meta.thumbnail'),
            ])
            ->with('seamless', true)
            ->priority(2)
            ->register();

        Metabox::make()
            ->id('excerpt')
            ->location('editor.side')
            ->when(fn ($post) => $post->supports('excerpt'))
            ->fields(fn ($post) => [
                Textarea::make('Excerpt')
                    ->model('meta.excerpt')
                    ->value(fn () => $post->meta('excerpt')),
            ])
            ->with('seamless', true)
            ->priority(3)
            ->register();

        Metabox::make()
            ->id('slug')
            ->location('editor.side')
            ->when(fn ($post) => $post->supports('route'))
            ->fields(fn ($post) => [
                Text::make('Slug')
                    ->model('name')
                    ->value($post->name)
                    ->fill(),
            ])
            ->with('seamless', true)
            ->priority(6)
            ->register();

        Metabox::make()
            ->id('parent')
            ->location('editor.side')
            ->when(fn ($post) => $post->supports('hierarchical'))
            ->fields(fn ($post) => [
                Number::make('Parent')
                    ->model('parent')
                    ->value($post->parent_id)
                    ->fill(),
            ])
            ->with('seamless', true)
            ->priority(9)
            ->register();
    }
}
