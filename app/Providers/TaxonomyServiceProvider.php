<?php

namespace App\Providers;

use App\Facades\Taxonomy;
use Illuminate\Support\ServiceProvider;

class TaxonomyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Taxonomy::registerFromClasses(config('taxonomies'));
    }
}
