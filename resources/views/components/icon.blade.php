@props(['name'])

@php
    if (!$name) {
        return;
    }

    $svg = File::get(resource_path("icons/{$name}.svg"));
    $attrString = $attributes->toHtml();
    echo str_replace('<svg', '<svg ' . $attrString, $svg);
@endphp
