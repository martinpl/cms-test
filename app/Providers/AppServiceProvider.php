<?php

namespace App\Providers;

use App\PostType;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // TODO: Move out to dedicated post type classes with register via config
        PostType::register('page', [
            'title' => __('Page'),
            'plural' => __('Pages'),
            'icon' => 'document-text',
            'route' => false, // TODO: false probably should disable single post page
        ]);

        PostType::register('post', [
            'title' => __('Post'),
            'plural' => __('Posts'),
            'icon' => 'newspaper',
        ]);
    }
}
