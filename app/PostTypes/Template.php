<?php

namespace App\PostTypes;

use App\Facades\BlockType;
use App\Facades\Hook;
use App\Facades\Metabox;
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
        // TODO: Add post type "support"
        Metabox::register('template', false, 'editor.side.page',
            fn () => view('components.fields.fields', [
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
            ])
        );
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
            if ($post->meta('template')) { // TODO: Add post type "support"
                $template = Template::find($post->meta('template'));
                $content = str_replace('__TEMPLATE__', $content, $template->content);
            }

            return $content;
        });
    }
}
