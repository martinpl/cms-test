<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class BlockType extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Foundation\BlockType::class;
    }
}
