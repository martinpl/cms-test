<?php

namespace App\Providers;

use App\AdminMenu\AdminMenu;
use App\Plugin;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class PluginServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        app('menu.admin')->add(AdminMenu::make('Plugins')
            ->route()
            ->order(1)
            ->icon('puzzle-piece'));

        $this->load();
    }

    protected function load()
    {
        foreach (Plugin::list() as $path => $meta) {
            require $path;
            $pluginName = Str::studly(basename($path, '.php'));
            new $pluginName;
        }
    }
}
