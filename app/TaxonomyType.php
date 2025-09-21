<?php

namespace App;

class TaxonomyType
{
    public private(set) array $list;

    public function register($taxonomyType, $args = [])
    {
        if (isset($this->list[$taxonomyType])) {
            throw new \Exception("Taxonomy type '{$taxonomyType}' already exists.");
        }

        $defaults = [
            'name' => $taxonomyType,
            'title' => __('Taxonomy'),
            'plural' => __('Taxonomies'),
            'hierarchical' => false, // TODO
            'order' => 0, // TODO
            'post_types' => [],
        ];

        $this->list[$taxonomyType] = array_merge($defaults, $args);
    }

    public function find($name)
    {
        if (! empty($this->list[$name])) {
            return $this->list[$name];
        }

        return null;
    }

    public function findForPostType($postType)
    {
        return array_filter($this->list, fn ($taxonomy) => in_array($postType, $taxonomy['post_types']));
    }
}
