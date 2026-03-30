<?php

namespace App\Taxonomies;

use App\PostTypes\Post;

class Tag extends Taxonomy
{
    public static $type = 'tag';

    public static function config()
    {
        return [
            'title' => __('Tag'),
            'plural' => __('Tags'),
            'hierarchical' => false,
            'post_types' => [Post::$type],
        ];
    }
}
