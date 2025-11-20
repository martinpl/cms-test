<?php

namespace App\View\Components\Fields;

class Checkbox extends Field
{
    public function getValue()
    {
        return (bool) ($this->value)();
    }
}
