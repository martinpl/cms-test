<?php

use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
 
new class extends Livewire\Component {
    public array $data;

    #[Locked]
    public $fields;

    public function mount()
    {
        foreach ($this->fields() as $field) {
            $this->data[$field->name] = $field->getValue();
        }
    }

    public function fields()
    {
        return ($this->fields)();
    }

    public function submit()
    {
        $validation = [];
        foreach ($this->fields() as $field) {
            if ($field->rules) {
                $validation['data.'.$field->name] = $field->rules;
            }
        }
        
        if ($validation) {
            $this->validate($validation);
        }

        foreach ($this->fields() as $field) {
            ($field->set)($this->data[$field->name]);
        }
    }
} ?>
 
<form wire:submit="submit">
    @foreach($this->fields() as $field)
        {{ $field }}
    @endforeach
    <flux:button type="submit">Submit</flux:button>
</form>