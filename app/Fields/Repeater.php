<?php

namespace App\Fields;

class Repeater extends Field
{
    public $value {
        get {
            return ($this->value)() ?? [];
        }
    }

    public $schema;

    public function schema($schema)
    {
        $this->schema = $schema;

        return $this;
    }

    public function render()
    {
        $componentName = strtolower(class_basename($this));
        echo view("components.fields.{$componentName}", [
            'model' => "{$this->model}.{$this->name}",
            'title' => $this->title,
            'schema' => $this->schema,
        ]);
    }
}
