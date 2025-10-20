<?php

namespace App\PostTypes;

class Attachment extends PostType
{
    public static $type = 'attachment';

    public static function config()
    {
        return [
            'title' => __('Media'),
            'plural' => __('Media'),
            'icon' => 'rectangle-stack',
            'route' => false,
            'list' => 'list-media',
            'editor' => 'media-editor',
        ];
    }
}
