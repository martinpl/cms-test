<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Metabox extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Foundation\MetaboxManager::class;
    }
}
