<?php

namespace App;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Theme
{
    public static function list(): array
    {
        $list = [];
        foreach (File::directories(base_path('themes')) as $path) {
            $pluginName = Str::studly(basename($path, '.php'));
            $file = "{$path}/{$pluginName}.php";
            if (! File::exists($file)) {
                continue;
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

            $meta['slug'] = basename($path);

            $list[$file] = $meta;
        }

        return $list;
    }
}
