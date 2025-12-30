@aware(['name'])

{{ $attributes->merge([
        'data-slot' => 'dialog-close',
        'x-data' => '',
        '@click.prevent' => '$dispatch("close-dialog", "' . $name . '")',
    ])->asChild($slot) }}
