<?php

namespace App\Taxonomies;

class Menu extends Taxonomy
{
    public static $type = 'menu';

    public static function config()
    {
        return [
            'title' => __('Menu'),
            'plural' => __('Menus'),
            'public' => false,
            'post_types' => [\App\PostTypes\MenuItem::$type],
        ];
    }

    public static function items($menu)
    {
        $term = self::query()
            ->whereMetaIn('locations', $menu)
            ->first();

        if (! $term) {
            return collect();
        }

        return $term->posts()
            ->whereNull('parent_id')
            ->with(['children' => function ($q) {
                $q->where('type', \App\PostTypes\MenuItem::$type)
                    ->orderBy('order');
            }])
            ->orderBy('order') // TODO: Should be default
            ->get();
    }
}
