<?php

namespace App\Schema;

use App\Fields\Checkbox;
use App\Fields\Media;
use App\Fields\Repeater;
use App\Fields\Text;

class Settings
{
    public static function fields()
    {
        return [
            Text::make('Site Title')
                ->option('site_title', autoload: true),
            Media::make('Site Icon')
                ->option('site_icon', autoload: true),
            Checkbox::make('Search engine visibility')
                ->option('search_engine_visibility', autoload: true),
            Repeater::make('Site Repeater')
                ->schema([
                    Text::make('Title'),
                    Media::make('Image'),
                    Repeater::make('Repeater')
                        ->schema([
                            Text::make('Title'),
                            Media::make('Image'),
                        ]),
                ])
                ->option('site_repeater', autoload: true),
        ];
    }
}
