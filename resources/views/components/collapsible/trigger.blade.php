{{ $attributes->merge([
        'data-slot' => 'collapsible-trigger',
        '@click' => 'collapsible = !collapsible',
    ])->asChild($slot) }}
