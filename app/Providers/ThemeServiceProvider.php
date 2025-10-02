<?php

namespace App\Providers;

use App\AdminMenu\AdminMenu;
use App\Theme;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        app('menu.admin')->add(AdminMenu::make('Themes')
            ->route()
            ->order(1)
            ->icon('paint-brush'));

        $this->load();
    }

    protected function load()
    {
        if (app()->runningInConsole()) {
            return;
        }

        $currentTheme = get_option('theme');
        foreach (Theme::list() as $path => $theme) {
            if ($currentTheme != $theme['slug']) {
                continue;
            }

            $pluginName = basename($path, '.php');
            require $path;
            new $pluginName;
        }
    }
}
