@aware(['name'])

{{ $attributes->merge([
        'data-slot' => 'dialog-trigger',
        'x-data' => '',
        '@click.prevent' => '$dispatch("open-dialog", "' . $name . '")',
    ])->asChild($slot) }}
