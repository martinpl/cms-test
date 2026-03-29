<?php

namespace App;

use App\Facades\BlockType;
use App\Facades\Hook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

class BlockEditor
{
    public static function register()
    {
        self::contentRead();
        self::contentSave();
    }

    protected static function contentRead()
    {
        // TODO: Add hook attributes
        Hook::addFilter('post.content', function ($content, $post): string {
            $editor = app(PostTypeRegistry::class)->find($post->type)['editor']; // TODO: awkward
            if ($editor != 'editor') {
                return $content;
            }

            if (! $content) {
                return '';
            }

            $html = '';
            $blocks = json_decode($content, true);
            foreach ($blocks as $index => $block) {
                $blockType = BlockType::find($block['name']);
                $html .= $blockType['render']($block);
            }

            return $html;
        });
    }

    protected static function contentSave()
    {
        // TODO: Type will not be available if we provide it in wrong order (after content) on save. I'm not sure if I like passing $attributes anyway
        Hook::addFilter('post.save.content', function ($content, $post) {
            $editor = app(PostTypeRegistry::class)->find($post->type)['editor'];
            if ($editor != 'editor') {
                return $content;
            }

            return $content ? json_encode($content) : null;
        });
    }

    public static function resolveComponent(array $block)
    {
        $class = 'Components\\'.Str::studly($block['name']).'\\'.Str::studly($block['name']);
        if (class_exists($class)) {
            return Blade::renderComponent(new $class($block['data']));
        } else {
            return view('components.'.Str::slug($block['name']).'.'.Str::slug($block['name']), $block['data'])->render();
        }
    }
}
