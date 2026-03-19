<?php

namespace App\PostTypes;

use Illuminate\Support\Facades\Blade;

class Pattern extends PostType
{
    public static $type = 'pattern';

    public static function config()
    {
        return [
            'title' => __('Pattern'),
            'plural' => __('Patterns'),
            'icon' => 'blocks',
            'route' => false,
        ];
    }

    public static function register()
    {
        // TODO: Use dropdown as input
        // TODO: Prevent stacking in infinite loop
        app(\App\BlockType::class)->register('pattern', [
            'name' => 'Pattern',
            'render' => function ($block) {
                return Blade::render(<<<'BLADE'
                    {!! App\PostTypes\Pattern::find($block['data']['pattern'] ?? null)?->content !!}
                BLADE, compact('block'));
            },
            'edit' => function ($block) {
                return Blade::render(<<<'BLADE'
                    <div class="p-4 md:p-6" x-show="selected == {{ $block['index'] }}" x-cloak @click.outside="selected = null">
                        <x-fields.fields :fields="[App\View\Components\Fields\Text::make('Pattern')]" :live="true" model="content.{$block['index']}.data" />
                    </div>
                    <div x-show="selected != {{ $block['index'] }}">
                        {!! App\PostTypes\Pattern::find($block['data']['pattern'] ?? null)?->content !!}
                    </div>
                BLADE, compact('block'));
            },
        ]);
    }
}
