<?php

namespace App\PostTypes;

use App\Facades\Taxonomy;

class Post extends PostType
{
    public static $type = 'post';

    public static function config()
    {
        return [
            'title' => __('Post'),
            'plural' => __('Posts'),
            'icon' => 'newspaper',
        ];
    }

    public static function register()
    {
        Taxonomy::registerFromClasses([
            \App\Taxonomies\Category::class,
            \App\Taxonomies\Tag::class,
        ]);
    }
}
