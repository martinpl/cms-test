<?php

namespace App\Providers;

use App\AdminMenu\AdminMenu;
use App\Facades\BlockType;
use App\Theme;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Livewire\Livewire;

class ThemeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        app('menu.admin')->add(AdminMenu::make('Themes')
            ->livewire()
            ->order(1)
            ->icon('paintbrush'));

        View::addLocation(base_path('themes/'.get_option('theme')));
        Blade::anonymousComponentPath('themes/'.get_option('theme'));
        Livewire::addLocation(base_path('themes/'.get_option('theme').'/components'));
        $this->load();
        $this->registerBlocks();
    }

    protected function load()
    {
        if (app()->runningInConsole()) {
            return;
        }

        $currentTheme = get_option('theme');
        foreach (Theme::list() as $theme) {
            if ($currentTheme != $theme['slug']) {
                continue;
            }

            $class = str($theme['path'])
                ->replace(['/', '.php'], ['\\', ''])
                ->ucfirst()
                ->toString();

            app()->register($class);
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
        $files = collect(File::allFiles($path))
            ->filter(fn ($file) => str_ends_with($file->getFilename(), '.blade.php'));
        foreach ($files as $path) {
            $meta = extract_metadata($path, [
                'name' => 'Name',
            ]);

            if (empty($meta['name'])) {
                continue;
            }

            $slug = Str::slug($meta['name']);
            BlockType::register($slug, $meta);
        }
    }
}
