<?php

/**
 * Name: Debug Bar
 */

namespace Plugins\DebugBar;

use Illuminate\Support\ServiceProvider;

// TODO: Print only for admin
class DebugBar extends ServiceProvider
{
    public function register(): void
    {
        require __DIR__.'/vendor/autoload.php';
        $this->app->register(\Fruitcake\LaravelDebugbar\ServiceProvider::class); // TODO: Namespace external deps
        config(['debugbar.storage.enabled' => false]);
        // TODO: we should have helper for current plugin path?
    }
}
