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

            $list[$file] = $meta;
        }

        return $list;
    }
}
