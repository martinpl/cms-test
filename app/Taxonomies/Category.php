<?php

namespace App\Taxonomies;

use App\PostTypes\Post;

class Category extends Taxonomy
{
    public static $type = 'category';

    public static function config()
    {
        return [
            'title' => __('Category'),
            'plural' => __('Categories'),
            'hierarchical' => true,
            'post_types' => [Post::$type],
        ];
    }
}
