<?php

namespace App\View\Components\Fields;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;

abstract class Field extends Component implements Htmlable
{
    public $model = 'data';

    public $name;

    public $value;

    public $set;

    public $rules;

    public $live;

    public function __construct(public $title)
    {
        $this->attributes = new ComponentAttributeBag;
        $this->name = Str::slug($title);
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

    public function getWireModel()
    {
        return $this->attributes->whereStartsWith('wire:model')->first("{$this->model}.{$this->name}");
    }

    public function value(callable $value)
    {
        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        if ($this->value) {
            return ($this->value)();
        }

        return null;
    }

    public function set(callable $set)
    {
        $this->set = $set;

        return $this;
    }

    public function option($name, $autoload = false)
    {
        $this->value(fn () => get_option($name));
        $this->set(fn ($value) => set_option($name, $value, $autoload));

        return $this;
    }

    public function rules($rules)
    {
        $this->rules = $rules;

        return $this;
    }

    public function live()
    {
        $this->live = true;

        return $this;
    }

    public function getLive()
    {
        return $this->live;
    }

    public function render()
    {
        $live = $this->live ? '.live.debounce.400ms' : '';
        $this->attributes->setAttributes(['wire:model'.$live => "{$this->model}.{$this->name}"]);

        $componentName = strtolower(class_basename($this));

        return view("components.fields.{$componentName}");
    }

    public function toHtml()
    {
        return Blade::render('components.fields.field-container', [
            'field' => $this,
        ]);
    }
}
