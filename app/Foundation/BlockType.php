<?php

namespace App\Foundation;

use App\BlockEditor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

class BlockType
{
    protected Collection $list;

    public function __construct()
    {
        $this->list = collect();
    }

    // TODO: Move to builder
    public function register($blockType, $args = []): void
    {
        // TODO: Namespace to prevent conflicts?
        if (isset($this->list[$blockType])) {
            throw new \Exception("Block type '{$blockType}' already exists.");
        }

        $defaults = [
            'name' => $blockType,
            'render' => function ($block) {
                return BlockEditor::resolveComponent($block);
            },
            'edit' => function ($block) {
                $class = 'Components\\'.Str::studly($block['name']).'\Schema';

                return Blade::render(<<<'BLADE'
                   @if (method_exists($class, 'fields') && $class::position() == 'content')
                        <div class="p-4 md:p-6" x-show="selected == {{ $block['index'] }}" x-cloak @click.outside="selected = null">
                            <x-fields :fields="$class::fields()" :live="true" model="content.{$block['index']}.data" />
                        </div>
                    @endif
                    <div @if ($class::position() == 'content') x-show="selected != {{ $block['index'] }}" @endif>
                        {!! \App\BlockEditor::resolveComponent($block) !!}
                    </div>
                BLADE, compact('block', 'class'));
            },
            'side' => function ($block) {
                $class = 'Components\\'.Str::studly($block['name']).'\Schema';

                return Blade::render(<<<'BLADE'
                    @if (method_exists($class, 'fields') && $class::position() == 'side')
                        <div x-show="selected == {{ $block['index'] }}" x-cloak>
                            <x-fields :fields="$class::fields()" :live="true" model="content.{$block['index']}.data" />
                        </div>
                    @endif
                BLADE, compact('block', 'class'));
            },
            'postTypes' => [],
        ];

        $this->list[$blockType] = array_merge($defaults, $args);
    }

    public function find($name): ?array
    {
        if (! empty($this->list[$name])) {
            return $this->list[$name];
        }

        return null;
    }

    public function list(): Collection
    {
        return $this->list;
    }
}
