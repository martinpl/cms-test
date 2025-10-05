<?php

namespace App\Providers;

use App\Http\Controllers\PostTypeController;
use App\PostType;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PostTypeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->addRoutes();
    }

    protected function addRoutes()
    {
        $this->app->booted(function () {
            foreach (app(PostType::class)->list as $postType) {
                if ($postType['route'] === false) {
                    continue;
                }

                // TODO: support custom controller
                Route::get("{$postType['route']}/{name}", PostTypeController::class)
                    ->name("single.{$postType['name']}")
                    ->defaults('postTypeName', $postType['name']);
            }
        });
    }
}
