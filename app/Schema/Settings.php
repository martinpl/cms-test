<?php

namespace App\Schema;

use App\Fields\Checkbox;
use App\Fields\Text;

class Settings
{
    public static function fields()
    {
        return [
            Text::make('Site Title')
                ->option('site_title', autoload: true),
            Checkbox::make('Search engine visibility')
                ->option('search_engine_visibility', autoload: true),
        ];
    }
}
