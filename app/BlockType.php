<?php

namespace App;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

// TODO: Move to facade?
class BlockType
{
    public private(set) array $list;

    // TODO: Move to builder
    public function register($blockType, $args = [])
    {
        // TODO: Namespace to prevent conflicts?
        if (isset($this->list[$blockType])) {
            throw new \Exception("Block type '{$blockType}' already exists.");
        }

        $defaults = [
            'name' => $blockType,
        ];

        $this->list[$blockType] = array_merge($defaults, $args);
    }

    public function find($name)
    {
        if (! empty($this->list[$name])) {
            return $this->list[$name];
        }

        return null;
    }

    // TODO: move out
    public static function render(string $json)
    {
        $html = '';
        $blocks = json_decode($json, true);
        foreach ($blocks as $block) {
            $html .= self::resolveComponent($block['name'], $block['data']);
        }

        return $html;
    }

    // TODO: move out
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
