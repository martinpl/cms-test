<?php

namespace App\Providers;

use App\AdminMenu\AdminMenu;
use App\AdminMenu\AdminMenuList;
use App\Models\Option;
use App\PostType;
use App\TaxonomyType;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PostType::class);
        $this->app->singleton(TaxonomyType::class);
        $this->app->singleton('menu.admin', fn () => new AdminMenuList);
        $this->app->singleton('menu.admin-bar', fn () => new AdminMenuList);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
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

        // TODO: Move out to dedicated post type classes with register via config
        app(PostType::class)->register('attachment', [
            'title' => __('Media'),
            'plural' => __('Media'),
            'icon' => 'rectangle-stack',
            'route' => false,
        ]);

        app(PostType::class)->register('page', [
            'title' => __('Page'),
            'plural' => __('Pages'),
            'icon' => 'document-text',
            'route' => '',
        ]);

        app(PostType::class)->register('post', [
            'title' => __('Post'),
            'plural' => __('Posts'),
            'icon' => 'newspaper',
        ]);

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
