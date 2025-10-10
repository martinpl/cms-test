<?php

namespace App\Schema;

use App\Fields\Text;

// TODO: Move to theme component namespace
class Hero
{
    public static function fields()
    {
        return [
            Text::make('Title'),
        ];
    }
}
