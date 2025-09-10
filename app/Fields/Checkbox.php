<?php

namespace App\Fields;

class Checkbox extends Field
{
    public $value {
        get {
            return (bool) ($this->value)();
        }
    }
}
