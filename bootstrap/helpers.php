<?php

use App\Models\Option;

// TODO: Move bodys to class
function get_option($name, $default = null): mixed
{
    if (isset(app('options')[$name])) {
        return app('options')[$name];
    }

    $option = Option::where('name', $name)->first();

    return $option ? $option->value : $default;
}

function set_option($name, $value, $autoload = null): bool
{
    $isEmpty = $value === '';
    if ($isEmpty) {
        return delete_option($name);
    }

    $values = ['value' => $value];
    if (! is_null($autoload)) {
        $values['autoload'] = $autoload;
    }
    $option = Option::updateOrCreate(['name' => $name], $values);

    if (isset(app('options')[$name])) {
        app()->instance('options', array_merge(app('options'), [$name => $value]));
    }

    return $option->wasChanged() || $option->wasRecentlyCreated;
}

function delete_option($name): bool
{
    $options = app('options');
    if (isset($options[$name])) {
        unset($options[$name]);
        app()->instance('options', $options);
    }

    return Option::where('name', $name)->delete() > 0;
}

function extract_metadata(string $file, array $defaultHeaders): array
{
    $fp = fopen($file, 'r');
    $fileData = fread($fp, 8192);
    fclose($fp);
    $headers = [];

    foreach ($defaultHeaders as $field => $regex) {
        if (preg_match('/'.$regex.':(.*)$/mi', $fileData, $match)) {
            $headers[$field] = trim($match[1]);
        } else {
            $headers[$field] = null;
        }
    }

    return $headers;
}
