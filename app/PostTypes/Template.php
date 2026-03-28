<?php

namespace App\PostTypes;

use App\Hook;
use App\View\Components\Fields\NativeSelect;
use Illuminate\View\ComponentAttributeBag;

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
        self::registerMetabox();
        self::registerContentBlock();
        self::adjustPostContent();
    }

    protected static function registerMetabox()
    {
        app(\App\MetaboxRegistry::class)->register(
            false,
            'editor.side.page', // TODO: Add post type "support"
            function () {
                return view('components.fields.fields', [
                    'attributes' => new ComponentAttributeBag(['class' => 'px-4 pb-4']),
                    'fields' => [
                        NativeSelect::make('Template')
                            ->model('meta')
                            // TODO: Add combobox / search
                            ->options(Template::all()
                                ->mapWithKeys(fn ($item) => [
                                    $item->id => $item->name,
                                ])->all()),
                    ],
                ]);
            },
        );
    }

    protected static function registerContentBlock()
    {
        app(\App\BlockType::class)->register('content', [
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
        app(Hook::class)->addFilter('post.content', function ($content, $post): string {
            if ($post->meta('template')) { // TODO: Add post type "support"
                $template = Template::find($post->meta('template'));
                $content = str_replace('__TEMPLATE__', $content, $template->content);
            }

            return $content;
        });
    }
}
