<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PostType extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Foundation\PostType::class;
    }
}
