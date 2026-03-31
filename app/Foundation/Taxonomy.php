<?php

namespace App\Foundation;

use Illuminate\Support\Collection;

class Taxonomy
{
    protected Collection $list;

    public function __construct()
    {
        $this->list = collect();
    }

    public function register(string $taxonomy, array $args = []): void
    {
        if (isset($this->list[$taxonomy])) {
            throw new \Exception("Taxonomy type '{$taxonomy}' already exists.");
        }

        $defaults = [
            'name' => $taxonomy,
            'title' => __('Taxonomy'),
            'plural' => __('Taxonomies'),
            'label' => $args['plural'] ?? __('Taxonomies'),
            'hierarchical' => false, // TODO
            'public' => true,
            'order' => 0, // TODO
            'post_types' => [],
            'editor' => 'taxonomies',
        ];

        $this->list[$taxonomy] = array_merge($defaults, $args);
    }

    public function registerFromClasses(array $taxonomies): void
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
        return $this->list->filter(fn ($taxonomy) => $taxonomy['name'] === $name)->first();

    }

    public function findForPostType(string $postType): Collection
    {
        return $this->list
            ->filter(fn ($taxonomy) => $taxonomy['public'])
            ->filter(fn ($taxonomy) => in_array($postType, $taxonomy['post_types']));
    }
}
