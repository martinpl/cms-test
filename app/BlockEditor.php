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
        app(Hook::class)->addFilter('post.content', function ($content, $attributes): string {
            $editor = app(PostTypeRegistry::class)->find($attributes['type'])['editor']; // TODO: awkward
            if ($editor != 'editor') {
                return $content;
            }

            if (! $content) {
                return '';
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
        // TODO: Type will not be available if we provide it in wrong order (after content) on save. I'm not sure if I like passing $attributes anyway
        app(Hook::class)->addFilter('post.save.content', function ($content, $attributes) {
            $editor = app(PostTypeRegistry::class)->find($attributes['type'])['editor'];
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
