<?php

use App\Plugin;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Computed;

new class extends Livewire\Component
{
    use App\Livewire\Table;

    protected function views()
    {
        return [
            'all' => 'All',
            'must-use' => 'Must-Use',
        ];
    }

    protected function counts()
    {
        return [
            'all' => $this->rawItems->where('mustUse', false)->count(),
            'must-use' => $this->rawItems->where('mustUse', true)->count(),
        ];
    }

    protected function columns()
    {
        return [
            'name' => 'Plugin',
            'description' => 'Description',
        ];
    }

    #[Computed]
    public function rawItems()
    {
        // TODO: list should be collection anyway
        return collect(App\Plugin::list())
            ->filter(function ($plugin) {
                return str_contains(strtolower($plugin['name']), $this->search);
            });

    }

    protected function items()
    {
        return $this->rawItems->reject(function ($plugin) {
            return $this->view == 'must-use' ? ! $plugin['mustUse'] : $plugin['mustUse'];
        });
    }

    private function columnName($plugin)
    {
        $name = $plugin['name'].$this->actions($plugin);
        $name = new HtmlString($name);

        return $name;
    }

    private function actions($plugin)
    {
        $actions = [];
        if ($plugin['mustUse']) {
            return;
        }

        if (Plugin::isActive($plugin['path'])) {
            $actions['deactivate'] = <<<HTML
                <button wire:click="deactivate('{$plugin['path']}')">
                    Deactivate
                </button>
            HTML;
        } else {
            $actions['activate'] = <<<HTML
                <button wire:click="activate('{$plugin['path']}')">
                    Active
                </button>
            HTML;
        }

        return $this->rowActions($actions);
    }

    private function columnDescription($plugin)
    {
        $meta = [];

        if ($plugin['version']) {
            $meta[] = "Version: {$plugin['version']}";
        }

        if ($plugin['author']) {
            $meta[] = "By {$plugin['author']}";
        }

        // TODO: Direct return give error from ass: "Cannot use "::class" on int" / file parsing issue?
        $description = new HtmlString($plugin['description'].'<br>'.implode(' | ', $meta));

        return $description;
    }

    public function activate($path)
    {
        Plugin::activate($path);
        $this->js('location.reload()');
    }

    public function deactivate($path)
    {
        Plugin::deactivate($path);
        $this->js('location.reload()');
    }
}; ?>

<x-slot:title>
    {{ __('Plugins') }}
</x-slot:title>

{{ $this->table(search: true) }}
