<?php

namespace App\Providers;

use App\AdminMenu\AdminMenu;
use App\AdminMenu\AdminMenuList;
use App\BlockEditor;
use App\BlockType;
use App\Hook;
use App\Models\Option;
use App\PostTypeRegistry;
use App\TaxonomyType;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ComponentAttributeBag;

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
        BlockEditor::register();
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
