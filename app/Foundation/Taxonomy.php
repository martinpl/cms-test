<?php

namespace App\Foundation;

class Taxonomy
{
    protected array $list;

    public function register(string $taxonomy, array $args = []): void
    {
        if (isset($this->list[$taxonomy])) {
            throw new \Exception("Taxonomy type '{$taxonomy}' already exists.");
        }

        $defaults = [
            'name' => $taxonomy,
            'title' => __('Taxonomy'),
            'plural' => __('Taxonomies'),
            'hierarchical' => false, // TODO
            'order' => 0, // TODO
            'post_types' => [],
            'editor' => 'taxonomies',
        ];

        $this->list[$taxonomy] = array_merge($defaults, $args);
    }

    public function registerClasses(array $taxonomies): void
    {
        foreach ($taxonomies as $taxonomy) {
            $this->register($taxonomy::$type, [
                ...$taxonomy::config(),
                'class' => $taxonomy,
            ]);
        }
    }

    public function find(string $name): ?array
    {
        if (! empty($this->list[$name])) {
            return $this->list[$name];
        }

        return null;
    }

    public function findForPostType(string $postType): array
    {
        return array_filter($this->list, fn ($taxonomy) => in_array($postType, $taxonomy['post_types']));
    }
}
