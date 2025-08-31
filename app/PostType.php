<?php

namespace App;

// TODO: Move to facade?
class PostType
{
    protected static $list = []; // TODO Asymmetric Visibility

    // TODO: Move to builder
    public static function register($postType, $args = [])
    {
        // TODO: Namespace to prevent conflicts?
        if (isset(self::$list[$postType])) {
            throw new \Exception("Post type '{$postType}' already exists.");
        }

        $defaults = [
            'name' => $postType,
            'title' => __('Post'),
            'plural' => __('Posts'),
            'icon' => 'square-2-stack',
            'order' => 0, // TODO
            'route' => $postType,
        ];

        $args = array_merge($defaults, $args);

        self::$list[$postType] = $args;
    }

    public static function list()
    {
        return self::$list;
    }

    public static function find($name)
    {
        if (! empty(self::$list[$name])) {
            return self::$list[$name];
        }

        return null;
    }
}
