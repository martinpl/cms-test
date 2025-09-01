<?php

namespace App;

// TODO: Move to facade?
class PostType
{
    public private(set) array $list;

    // TODO: Move to builder
    public function register($postType, $args = [])
    {
        // TODO: Namespace to prevent conflicts?
        if (isset($this->list[$postType])) {
            throw new \Exception("Post type '{$postType}' already exists.");
        }

        $defaults = [
            'name' => $postType,
            'title' => __('Post'),
            'plural' => __('Posts'),
            'icon' => 'square-2-stack',
            'order' => 0, // TODO
            'route' => $postType,
        ];

        $this->list[$postType] = array_merge($defaults, $args);
    }

    public function find($name)
    {
        if (! empty($this->list[$name])) {
            return $this->list[$name];
        }

        return null;
    }
}
