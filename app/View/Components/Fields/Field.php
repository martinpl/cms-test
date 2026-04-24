<?php

namespace App\View\Components\Fields;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;

abstract class Field extends Component implements Htmlable
{
    public $name;

    public $model;

    public $value;

    public $save;

    public $rules;

    public $live = false;

    public $fill;

    public function __construct(public $title)
    {
        $this->attributes = new ComponentAttributeBag;
        $this->name = Str::slug($title);
        $this->model = 'data.'.$this->name;
    }

    public static function make($name)
    {
        return new static($name);
    }

    public function model(string $model)
    {
        $this->model = $model;

        return $this;
    }

    public function value(mixed $value)
    {
        $this->value = $value;

        return $this;
    }

    public function save(callable $save)
    {
        $this->save = $save;

        return $this;
    }

    public function option($name, $autoload = false)
    {
        $this->value(fn () => get_option($name));
        $this->save(fn ($value) => set_option($name, $value, $autoload));

        return $this;
    }

    public function rules($rules)
    {
        $this->rules = $rules;

        return $this;
    }

    public function live(bool $live = true)
    {
        $this->live = $live;

        return $this;
    }

    public function fill()
    {
        $this->fill = true;

        return $this;
    }

    public function self()
    {
        return $this;
    }

    public function render()
    {
        $modifiers = '';

        if ($this->live) {
            $modifiers .= '.live.debounce.400ms';
        }

        if ($this->fill) {
            $modifiers .= '.fill';
        }

        $this->attributes->setAttributes(['wire:model'.$modifiers => $this->model]);
        $componentName = str(get_class($this))->classBasename()->kebab()->value;

        return view("components.fields.{$componentName}");
    }

    public function toHtml()
    {
        return Blade::render('components.fields.field-container', [
            'field' => $this,
        ]);
    }
}
