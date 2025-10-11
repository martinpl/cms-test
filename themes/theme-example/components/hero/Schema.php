<?php

namespace Components\Hero;

use App\Fields\Text;

class Schema
{
    public static function fields()
    {
        return [
            Text::make('Title'),
        ];
    }
}
