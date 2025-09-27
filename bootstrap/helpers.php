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
    $values = ['value' => $value];
    if (! is_null($autoload)) {
        $values['autoload'] = $autoload;
    }
    $option = Option::updateOrCreate(['name' => $name], $values);

    return $option->wasChanged() || $option->wasRecentlyCreated;
}

function delete_option($name): bool
{
    return Option::where('name', $name)->delete() > 0;
}
