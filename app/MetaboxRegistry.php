<?php

namespace App;

class MetaboxRegistry
{
    public private(set) array $metaboxes = [];

    public function register($title, $location, $view, $priority = 10)
    {
        $this->metaboxes[$location][$priority][] = [
            'title' => $title,
            'view' => $view,
        ];
    }

    public function get($location, $context): array
    {
        if (empty($this->metaboxes[$location])) {
            return [];
        }

        ksort($this->metaboxes[$location]);
        $metaboxes = [];
        foreach ($this->metaboxes[$location] as $metaboxGroup) {
            foreach ($metaboxGroup as $metabox) {
                $view = $metabox['view'] instanceof \Closure ? $metabox['view'](...$context) : $metabox['view']->with($context);
                $metabox['view'] = $view;
                $metaboxes[] = $metabox;
            }
        }

        return $metaboxes;
    }
}
