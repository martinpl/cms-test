<?php

namespace App;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Livewire\Livewire;

// TODO: contract?
class BlockEditor
{
    public static function render()
    {
        return Livewire::mount('editor', [
            'id' => request()->route('id'),
            'postType' => request()->route('postType'),
        ]);
    }

    public static function set($content)
    {
        if ($content) {
            return json_encode($content);
        }

        return $content;
    }

    public static function get($content)
    {
        $html = '';
        $blocks = json_decode($content, true);
        foreach ($blocks as $block) {
            $html .= self::resolveComponent($block['name'], $block['data']);
        }

        return $html;
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
