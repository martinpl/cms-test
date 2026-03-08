<?php

use App\MenuRegistry;

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
        app(MenuRegistry::class)->register([
            'primary' => 'Primary',
        ]);
    }
}
