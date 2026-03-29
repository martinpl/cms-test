<?php

namespace App\Foundation;

class Metabox
{
    protected array $metaboxes = [];

    public function register(string $id, string|false $title, string $location, callable $callback, int $priority = 10): void
    {
        $this->metaboxes[$location][] = [
            'id' => $id,
            'title' => $title,
            'callback' => $callback,
            'priority' => $priority,
        ];
    }

    public function get(string|array $location): array
    {
        $locations = (array) $location;
        $metaboxes = [];
        foreach ($locations as $location) {
            $metaboxes[] = $this->metaboxes[$location] ?? [];
        }

        $metaboxes = array_merge(...$metaboxes);
        usort($metaboxes, fn ($a, $b) => $a['priority'] <=> $b['priority']);

        return $metaboxes;
    }
}
