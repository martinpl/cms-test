<?php

namespace App\PostTypes;

// TODO: Side menu title
class MenuItem extends PostType
{
    public static $type = 'menu-item';

    public static function config()
    {
        return [
            'title' => __('Menu Item'),
            'plural' => __('Menu Items'),
            'icon' => 'compass',
            'list' => 'menu',
            'route' => false,
        ];
    }
}
