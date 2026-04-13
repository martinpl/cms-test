<?php

namespace App\Foundation;

use Illuminate\Support\Collection;

class MetaboxManager
{
    protected Collection $metaboxes;

    public function __construct()
    {
        $this->metaboxes = collect();
    }

    public function register(Metabox $metabox): void
    {
        $this->metaboxes[] = $metabox;
    }

    public function get(string|array $location, $args)
    {
        $metaboxes = $this->metaboxes->whereIn('location', $location)
            ->sortBy('priority');

        foreach ($metaboxes as $metabox) {
            $metabox->render($args);
        }
    }

    public static function make(): Metabox
    {
        return new Metabox;
    }
}
