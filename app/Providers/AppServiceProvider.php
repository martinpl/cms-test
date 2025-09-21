<?php

namespace App\Providers;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->singleton('options', function () {
            return Option::where('autoload', true)->select('name', 'value')->get()->pluck('value', 'name')->toArray();
        });

        // TODO: Move out to dedicated post type classes with register via config
        app(PostType::class)->register('page', [
            'title' => __('Page'),
            'plural' => __('Pages'),
            'icon' => 'document-text',
            'route' => false, // TODO: false probably should disable single post page
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
