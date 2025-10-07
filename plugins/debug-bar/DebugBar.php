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
        app()->register(\Barryvdh\Debugbar\ServiceProvider::class);
        config(['debugbar.storage.path' => __DIR__.'/storage']); // TODO: we should have helper for current plugin path?
    }
}
