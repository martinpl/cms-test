<?php

namespace App\Providers;

use App\TaxonomyType;
use Illuminate\Support\ServiceProvider;

class TaxonomyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        app(TaxonomyType::class)->registerClasses(config('taxonomies'));
    }
}
