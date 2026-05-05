<?php

namespace App\Providers;

use App\Facades\PostType;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostTypeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PostTypeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        PostType::registerFromClasses(config('post-types'));
        $this->addRoutes();
    }

    protected function addRoutes()
    {
        $this->app->booted(function () {
            Route::get('/', HomeController::class)
                ->middleware('web')
                ->name('home');

            foreach (PostType::list() as $postType) {
                if ($postType['route'] === false) {
                    continue;
                }

                // TODO: support custom controller
                Route::get("{$postType['route']}/{name}", PostTypeController::class)
                    ->middleware('web')
                    ->defaults('postTypeName', $postType['name'])
                    ->name("single.{$postType['name']}")
                    ->where('name', '.*');
            }
        });
    }
}
