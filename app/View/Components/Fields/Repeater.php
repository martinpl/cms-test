<?php

namespace App\View\Components\Fields;

class Repeater extends Field
{
    protected $schema;

    public function getValue()
    {
        return ($this->value)() ?? [];
    }

    public function schema($schema)
    {
        $this->schema = $schema;

        return $this;
    }

    public function getSchema()
    {
        return $this->schema;
    }

    public function render()
    {
        $componentName = strtolower(class_basename($this));

        return view("components.fields.{$componentName}");
    }
}
