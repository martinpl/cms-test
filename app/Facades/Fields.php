<?php

namespace App\Facades;

use App\Foundation\FieldsManager;
use Illuminate\Support\Facades\Facade;

class Fields extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FieldsManager::class;
    }
}
