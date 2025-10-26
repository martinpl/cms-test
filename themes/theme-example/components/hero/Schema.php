<?php

namespace Components\Hero;

use App\Fields\Media;
use App\Fields\Repeater;
use App\Fields\Text;

class Schema
{
    public static function fields()
    {
        return [
            Text::make('Title'),
            Media::make('Image'),
            Repeater::make('Repeater')
                ->schema([
                    Text::make('Title'),
                    Media::make('Image'),
                    Repeater::make('Repeater')
                        ->schema([
                            Text::make('Title'),
                            Media::make('Image'),
                        ]),
                ]),
        ];
    }
}
