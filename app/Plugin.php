<?php

namespace App;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Plugin
{
    public static function list(): array
    {
        $paths = [
            ...glob(base_path('mu-plugins/*')),
            ...glob(base_path('plugins/*')),
        ];
        $list = [];

        foreach ($paths as $path) {
            $pluginName = Str::studly(basename($path, '.php'));

            if (str_ends_with($path, '.php')) {
                $file = $path;
            }

            if (! str_ends_with($path, '.php')) {
                $file = "{$path}/{$pluginName}.php";
                if (! File::exists($file)) {
                    continue;
                }
            }

            $meta = extract_metadata($file, [
                'name' => 'Name',
                'version' => 'Version',
                'author' => 'Author',
                'description' => 'Description',
            ]);

            if (! $meta['name']) {
                $meta['name'] = $pluginName;
            }

            $basePath = Str::after($file, base_path('/'));
            $list[] = [
                ...$meta,
                'path' => $basePath,
                'mustUse' => str_starts_with($basePath, 'mu-plugins/'),
            ];
        }

        return $list;
    }

    public static function activeList()
    {
        return get_option('active_plugins', []);
    }

    public static function activate($path)
    {
        if (self::isActive($path)) {
            return;
        }

        $plugins = self::activeList();
        $plugins[] = $path;
        set_option('active_plugins', $plugins, true);
    }

    public static function deactivate($path)
    {
        $plugins = array_filter(self::activeList(), fn ($value) => $value !== $path);
        set_option('active_plugins', $plugins, true);
    }

    public static function isActive($path): bool
    {
        return in_array($path, self::activeList($path));
    }
}
