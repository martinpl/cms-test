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
        $this->classAutoloader('themes/'.get_option('theme').'/components', 'Components');
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
        $theme = get_option('theme');
        if (! $theme) {
            return;
        }

        $path = base_path("themes/{$theme}/components");
        $dirs = File::directories($path);
        foreach ($dirs as $dir) {
            $basename = basename($dir);
            $meta = extract_metadata("$dir/{$basename}.blade.php", [
                'name' => 'Name',
            ]);

            if (empty($meta['name'])) {
                continue;
            }

            $slug = Str::slug($meta['name']);
            app(\App\BlockType::class)->register($slug, $meta);
        }
    }

    protected function classAutoloader($directory, $namespace)
    {
        spl_autoload_register(function ($class) use ($directory, $namespace) {
            if (str_starts_with($class, $namespace)) {
                $path = str_replace("$namespace\\", '', $class);
                $parts = explode('\\', $path);
                $parts[0] = Str::kebab($parts[0]);
                $path = implode('/', $parts);
                $file = base_path("{$directory}/{$path}.php");
                if (file_exists($file)) {
                    include $file;
                }
            }
        });
    }
}
