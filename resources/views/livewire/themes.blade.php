<?php

use Illuminate\Support\HtmlString;

new class extends Livewire\Component
{
    use App\Livewire\Table;

    public function columns()
    {
        return [
            'theme' => 'Theme',
            'description' => 'Description',
        ];
    }

    public function items()
    {
        return collect(\App\Theme::list())
            ->map(fn ($plugin, $index) => array_merge($plugin, ['id' => $index]))
            ->map(fn ($item) => (object) $item);
    }

    public function columnTheme($theme)
    {
        $name = $theme->name.$this->actions($theme);

        return new HtmlString($name);
    }

    private function actions($theme)
    {
        $currentTheme = get_option('theme');
        if ($currentTheme == $theme->slug) {
            $actions['deactivate'] = <<<'HTML'
                <button wire:click="setTheme('')">Deactivate</button>
            HTML;
        } else {
            $actions['activate'] = <<<HTML
                <button wire:click="setTheme('{$theme->slug}')">Active</button>
            HTML;
        }

        return $this->rowActions($actions);
    }

    private function columnDescription($theme)
    {
        $meta = [];

        if ($theme->version) {
            $meta[] = "Version: {$theme->version}";
        }

        if ($theme->author) {
            $meta[] = "By {$theme->author}";
        }

        return new HtmlString($theme->description.'<br>'.implode(' | ', $meta));
    }

    public function setTheme($name)
    {
        set_option('theme', $name, true);
    }
}; ?>

<x-slot:title>
    {{ __('Themes') }}
</x-slot:title>

{{ $this->table() }}
