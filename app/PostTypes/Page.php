<?php

namespace App\PostTypes;

class Page extends PostType
{
    public static $type = 'page';

    public static function config()
    {
        return [
            'title' => __('Page'),
            'plural' => __('Pages'),
            'icon' => 'document-text',
            'route' => '',
        ];
    }
}
