<?php

namespace App\Fields;

class Media extends Field
{
    public function render()
    {
        $componentName = strtolower(class_basename($this));
        echo view("components.fields.{$componentName}", [
            'model' => "{$this->model}.{$this->name}",
            'title' => $this->title,
            'value' => $this->value,
        ]);
    }
}
