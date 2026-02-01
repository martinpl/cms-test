<?php

/**
 * Name: Debug Bar
 */
// TODO: Print only for admin
class DebugBar
{
    public function __construct()
    {
        require __DIR__.'/vendor/autoload.php';
        app()->register(\Fruitcake\LaravelDebugbar\ServiceProvider::class);
        config(['debugbar.storage.enabled' => false]);
        // TODO: we should have helper for current plugin path?
    }
}
