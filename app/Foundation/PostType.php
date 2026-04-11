<?php

namespace App\Foundation;

use Illuminate\Support\Collection;

class PostType
{
    protected Collection $list;

    public function __construct()
    {
        $this->list = collect();
    }

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
            'label' => $args['plural'] ?? __('Posts'),
            'icon' => 'square-2-stack',
            'order' => 0, // TODO
            'route' => $postType,
            'editor' => 'editor',
            'list' => 'list',
            'class' => null,
            'supports' => [],
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
        return $this->list->filter(fn ($postType) => $postType['name'] == $name)->first();
    }

    public function list(): Collection
    {
        return $this->list;
    }

    public function supports(string $name, string $feature): bool
    {
        $postType = $this->find($name);
        if (! $postType) {
            return false;
        }

        return in_array($feature, $postType['supports']);
    }

    public function addSupport(string $name, string|array $feature)
    {
        $postType = $this->find($name);
        if (! $postType) {
            return;
        }

        $postType['supports'] = array_merge($postType['supports'], (array) $feature);
        $this->list->put($name, $postType);
    }

    public function removeSupport(string $name, string|array $feature)
    {
        $postType = $this->find($name);
        if (! $postType) {
            return;
        }

        $postType['supports'] = array_diff($postType['supports'], (array) $feature);
        $this->list->put($name, $postType);
    }
}
