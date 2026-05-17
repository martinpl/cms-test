<?php

namespace App\Providers;

use App\AdminMenu\AdminMenu;
use App\Plugin;
use Illuminate\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (app()->runningInConsole()) { // TODO: That should be check for migration command
            return;
        }

        app('menu.admin')->add(AdminMenu::make('Plugins')
            ->livewire()
            ->order(1)
            ->icon('puzzle'));

        $this->load();
    }

    protected function load()
    {
        foreach (Plugin::list() as $meta) {
            if ($meta['mustUse'] !== 'true' && ! Plugin::isActive($meta['path'])) {
                return;
            }

            $class = str($meta['path'])
                ->replace(['/', '.php'], ['\\', ''])
                ->ucfirst()
                ->toString();

            app()->register($class);
        }
    }
}
