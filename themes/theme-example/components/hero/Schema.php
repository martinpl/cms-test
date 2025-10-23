<?php

namespace Components\Hero;

use App\Fields\Media;
use App\Fields\Text;

class Schema
{
    public static function fields()
    {
        return [
            Text::make('Title'),
            Media::make('Image'),
        ];
    }
}
