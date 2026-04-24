<?php

namespace App\View\Components\Fields;

class Repeater extends Field
{
    public $schema;

    public function schema($schema)
    {
        $this->schema = $schema;

        return $this;
    }

    public function render()
    {
        $componentName = strtolower(class_basename($this));

        return view("components.fields.{$componentName}");
    }
}
