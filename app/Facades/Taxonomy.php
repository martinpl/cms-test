<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Taxonomy extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Foundation\Taxonomy::class;
    }
}
