<?php

use App\Facades\Menu;

/**
 * Name: Theme Example
 * Version: 1.0.0
 * Author: John Doe
 * Description: Example theme
 */
class ThemeExample
{
    public function __construct()
    {
        Menu::register([
            'primary' => 'Primary',
        ]);
    }
}
