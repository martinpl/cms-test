<?php

namespace App\PostTypes;

use App\Facades\BlockType;
use App\Facades\Hook;
use App\Facades\Metabox;
use App\View\Components\Fields\NativeSelect;

class Template extends PostType
{
    public static $type = 'template';

    public static function config()
    {
        return [
            'title' => __('Template'),
            'plural' => __('Templates'),
            'icon' => 'panels-top-left',
            'route' => false,
            'template' => [
                ['content'],
            ],
        ];
    }

    public static function register()
    {
        Page::addSupport('template');
        Post::addSupport('template');
        self::registerMetabox();
        self::registerContentBlock();
        self::adjustPostContent();
    }

    protected static function registerMetabox()
    {
        Metabox::make()
            ->id('template')
            ->location('editor.side')
            ->when(fn ($post): mixed => $post->supports('template'))
            ->wrapper('components.metabox-side-item')
            ->fields([
                NativeSelect::make('Template')
                    ->model('meta') // TODO
                    // TODO: Add combobox / search / lazy
                    ->options(Template::all()
                        ->mapWithKeys(fn ($item) => [
                            $item->id => $item->name,
                        ])->all()
                    ),
            ])
            ->register();
    }

    protected static function registerContentBlock()
    {
        BlockType::register('content', [
            'name' => 'Content',
            'postTypes' => [Template::$type],
            'render' => function () {
                return '__TEMPLATE__';
            },
            'edit' => function () {
                return <<<'HTML'
                    <div class="p-4 md:p-6"  @click.outside="selected = null">
                        Post content
                    </div>
                HTML;
            },
        ]);
    }

    protected static function adjustPostContent()
    {
        Hook::addFilter('post.content', function ($content, $post): string {
            if ($post->supports('template') && $post->meta('template')) {
                $template = Template::find($post->meta('template'));
                $content = str_replace('__TEMPLATE__', $content, $template->content);
            }

            return $content;
        });
    }
}
