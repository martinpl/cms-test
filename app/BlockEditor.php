<?php

namespace App;

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
        app(Hook::class)->addFilter('post.content', function ($content, $post) {
            $editor = app(PostType::class)->find($post->type)['editor']; // TODO: awkward
            if ($editor != 'editor') {
                return $content;
            }

            $html = '';
            $blocks = json_decode($content, true);
            foreach ($blocks as $block) {
                $html .= self::resolveComponent($block['name'], $block['data']);
            }

            return $html;
        });
    }

    protected static function contentSave()
    {
        app(Hook::class)->addFilter('post.save.content', function ($content, $post) {
            $editor = app(PostType::class)->find($post->type)['editor'];
            if ($editor != 'editor') {
                return $content;
            }

            return $content ? json_encode($content) : null;
        });
    }

    public static function resolveComponent(string $name, array $data)
    {
        $class = 'Components\\'.Str::studly($name).'\\'.Str::studly($name);
        if (class_exists($class)) {
            return Blade::renderComponent(new $class(...$data));
        } else {
            return view('components.'.Str::slug($name).'.'.Str::slug($name), $data)->render();
        }
    }
}
