<?php

namespace App\PostTypes;

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
}
