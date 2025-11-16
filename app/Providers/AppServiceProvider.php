<?php

namespace App\Providers;

use App\AdminMenu\AdminMenu;
use App\AdminMenu\AdminMenuList;
use App\BlockType;
use App\Hook;
use App\Models\Option;
use App\PostTypeRegistry;
use App\TaxonomyType;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Hook::class);
        $this->app->singleton(PostTypeRegistry::class);
        $this->app->singleton(TaxonomyType::class);
        $this->app->singleton(BlockType::class);
        $this->app->singleton('menu.admin', fn () => new AdminMenuList);
        $this->app->singleton('menu.admin-bar', fn () => new AdminMenuList);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->runningInConsole()) { // TODO: That should be check for migration command
            return;
        }

        app('menu.admin')->add(AdminMenu::make(__('Dashboard'))
            ->route('/')
            ->icon('home'));

        app('menu.admin')->add(AdminMenu::make(__('Settings'))
            ->order(2)
            ->route()
            ->icon('adjustments-vertical'));

        $this->app->singleton('options', function () {
            return Option::where('autoload', true)->select('name', 'value')->get()->pluck('value', 'name')->toArray();
        });

        // TODO: Move out to dedicated taxonomies classes with register via config
        app(TaxonomyType::class)->register('category', [
            'title' => __('Category'),
            'plural' => __('Categories'),
            'hierarchical' => true,
            'post_types' => ['post'],
        ]);

        app(TaxonomyType::class)->register('tag', [
            'title' => __('Tag'),
            'plural' => __('Tags'),
            'hierarchical' => false,
            'post_types' => ['post'],
        ]);
    }
}
