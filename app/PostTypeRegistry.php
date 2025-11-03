<?php

namespace App;

// TODO: Move to facade?
class PostTypeRegistry
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
            'editor' => 'editor',
            'list' => 'list',
            'class' => null,
        ];

        $this->list[$postType] = array_merge($defaults, $args);
    }

    public function registerClasses($postTypes)
    {
        foreach ($postTypes as $postType) {
            $this->register($postType::$type, [
                ...$postType::config(),
                'class' => $postType,
            ]);
        }
    }

    public function find($name)
    {
        if (! empty($this->list[$name])) {
            return $this->list[$name];
        }

        return null;
    }
}
