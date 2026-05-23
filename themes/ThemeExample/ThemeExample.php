<?php

/**
 * Name: Theme Example
 * Version: 1.0.0
 * Author: John Doe
 * Description: Example theme
 */

namespace Themes\ThemeExample;

use App\Facades\Menu;
use Illuminate\Support\ServiceProvider;

class ThemeExample extends ServiceProvider
{
    public function __construct()
    {
        Menu::register([
            'primary' => 'Primary',
        ]);
    }
}
