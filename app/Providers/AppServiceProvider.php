<?php

namespace App\Providers;

use App\AdminMenu\AdminMenu;
use App\AdminMenu\AdminMenuList;
use App\BlockEditor;
use App\Models\Option;
use App\Role;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ComponentAttributeBag;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Foundation\Hook::class);
        $this->app->singleton(\App\Foundation\Metabox::class);
        $this->app->singleton(\App\Foundation\PostType::class);
        $this->app->singleton(\App\Foundation\Taxonomy::class);
        $this->app->singleton(\App\Foundation\BlockType::class);
        BlockEditor::register();
        $this->app->singleton('menu.admin', fn () => new AdminMenuList);
        $this->app->singleton('menu.admin-bar', fn () => new AdminMenuList);
        $this->app->singleton(Role::class);
        $this->app->singleton(\App\Foundation\Menu::class);
    }

    public function boot(): void
    {
        if (app()->runningInConsole()) { // TODO: That should be check for migration command
            return;
        }

        // TODO: Move out
        ComponentAttributeBag::macro('buttonTag', function () {
            $hasHref = $this->has('href') && $this->get('href') !== null;

            return $hasHref ? 'a' : 'button';
        });

        ComponentAttributeBag::macro('asChild', function ($slot) {
            return new HtmlString(preg_replace(
                '/^<([a-z0-9-]+)/i',
                '<$1 '.
                    $this,
                ltrim($slot),
                1,
            ));
        });

        app('menu.admin')->add(AdminMenu::make(__('Dashboard'))
            ->page('/')
            ->icon('house'));

        app('menu.admin')->add(AdminMenu::make(__('Settings'))
            ->order(2)
            ->livewire()
            ->icon('sliders-vertical'));

        $this->app->singleton('options', function () {
            return Option::where('autoload', true)->select('name', 'value')->get()->pluck('value', 'name')->toArray();
        });

    }
}
