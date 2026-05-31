<?php

namespace App\Foundation;

use App\BlockEditor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

class BlockType
{
    protected Collection $list;

    public function __construct()
    {
        $this->list = collect();
    }

    // TODO: Move to builder
    public function register($blockType, $args): void
    {
        // TODO: Namespace to prevent conflicts?
        if (isset($this->list[$blockType])) {
            throw new \Exception("Block type '{$blockType}' already exists.");
        }

        $class = $this->resolveClass($blockType);
        $defaults = [
            'name' => $blockType,
            'class' => $class,
            'render' => fn ($block) => BlockEditor::resolveComponent($block),
            'edit' => function ($block) {
                return Blade::render(<<<'BLADE'
                    @if ($fields = App\Facades\Fields::get('block', $block['name'], model: "content.{$block['index']}.data", live: true))
                        <div class="p-4 md:p-6" x-show="selected == {{ $block['index'] }}" x-cloak @click.outside="selected = null">
                            {{ $fields }}
                        </div>
                    @endif
                    <div @if ($fields) x-show="selected != {{ $block['index'] }}" @endif>
                        {!! \App\BlockEditor::resolveComponent($block) !!}
                    </div>
                BLADE, compact('block'));
            },
            'side' => function ($block) {
                return Blade::render(<<<'BLADE'
                    @if ($fields = App\Facades\Fields::get('block.side', $block['name'], model: "content.{$block['index']}.data", live: true))
                        <div x-show="selected == {{ $block['index'] }}" x-cloak>
                            {{ $fields }}
                        </div>
                    @endif
                BLADE, compact('block'));
            },
            'postTypes' => [],
        ];

        $this->list[$blockType] = array_merge($defaults, $args);

        if ($class && method_exists($class, 'register')) {
            $class::register();
        }
    }

    // Copy of Livewire\Factory\Factory->resolveComponentNameAndClass()
    // TODO: We may neeed cache as og code
    public function resolveClass($name)
    {
        $compiler = app('livewire.compiler');
        $finder = app('livewire.finder');

        $name = $finder->normalizeName($name);

        $class = null;

        if ($name) {
            $class = $finder->resolveClassComponentClassName($name);

            if (! $class) {
                $path = $finder->resolveMultiFileComponentPath($name);

                if (! $path) {
                    $path = $finder->resolveSingleFileComponentPath($name);
                }

                if ($path) {
                    $class = $compiler->compile($path);
                }
            }
        }

        return $class;
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
