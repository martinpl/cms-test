<?php

namespace App\Fields;

use Illuminate\Support\Str;

abstract class Field
{
    public $model = 'data';

    public $name;

    public $value {
        get {
            return ($this->value)();
        }
    }

    public $set;

    public $rules;

    public function __construct(public $title)
    {
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

    public function value(callable $value)
    {
        $this->value = $value;

        return $this;
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

    public function render()
    {
        $componentName = strtolower(class_basename($this));
        echo view("components.fields.{$componentName}", [
            'model' => "{$this->model}.{$this->name}",
            'title' => $this->title,
        ]);
    }
}
