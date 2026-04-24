<?php

namespace App\PostTypes;

use App\Facades\Taxonomy;
use App\Taxonomies\Category;
use App\Taxonomies\Tag;

class Post extends PostType
{
    public static $type = 'post';

    public static function config()
    {
        return [
            'title' => __('Post'),
            'plural' => __('Posts'),
            'icon' => 'newspaper',
            'supports' => ['home', 'thumbnail', 'excerpt', 'template', 'hierarchical'],
        ];
    }

    public static function register()
    {
        Taxonomy::registerFromClasses([
            Category::class,
            Tag::class,
        ]);
    }
}
