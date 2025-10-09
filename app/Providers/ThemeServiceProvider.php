<?php

namespace App\Providers;

use App\AdminMenu\AdminMenu;
use App\Theme;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ThemeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        app('menu.admin')->add(AdminMenu::make('Themes')
            ->route()
            ->order(1)
            ->icon('paint-brush'));

        View::addLocation(base_path('themes/'.get_option('theme')));
        Blade::anonymousComponentPath('themes/'.get_option('theme'));
        $this->load();
        $this->registerBlocks();
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

    // TODO: move out
    protected function registerBlocks()
    {
        $path = base_path('themes/'.get_option('theme').'/components');
        $files = File::files($path);
        foreach ($files as $file) {
            $meta = extract_metadata($file->getPathname(), [
                'name' => 'Name',
            ]);

            if (empty($meta['name'])) {
                continue;
            }

            $slug = Str::slug($meta['name']);
            app(\App\BlockType::class)->register($slug, $meta);
        }
    }
}
