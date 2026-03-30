<?php

namespace App\Foundation;

class PostType
{
    protected array $list;

    // TODO: Move to builder
    public function register(string $postType, array $args = []): void
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
            'template' => [],
        ];

        $this->list[$postType] = array_merge($defaults, $args);
    }

    public function registerFromClasses(array $postTypes): void
    {
        foreach ($postTypes as $postType) {
            $this->register($postType::$type, [
                ...$postType::config(),
                'class' => $postType,
            ]);

            $postType::register();
        }
    }

    public function find(string $name): ?array
    {
        if (! empty($this->list[$name])) {
            return $this->list[$name];
        }

        return null;
    }

    public function list(): array
    {
        return $this->list;
    }
}
