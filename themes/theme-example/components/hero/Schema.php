<?php

namespace Components\Hero;

use App\View\Components\Fields\Media;
use App\View\Components\Fields\Repeater;
use App\View\Components\Fields\Text;

class Schema
{
    public static function position()
    {
        // return 'side';
        return 'content';
    }

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
