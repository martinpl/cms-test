<?php

namespace App\View\Components\Fields;

class NativeSelect extends Field
{
    public $options;

    public function options($options)
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }
}
