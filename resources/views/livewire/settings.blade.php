<?php

use Livewire\Attributes\Computed;

new class extends Livewire\Component
{
    public array $data;

    public function mount()
    {
        foreach ($this->fields as $field) {
            $this->data[$field->name] = $field->getValue();
        }
    }

    #[Computed]
    public function fields()
    {
        return App\Schema\Settings::fields();
    }

    public function submit()
    {
        $validation = [];
        foreach ($this->fields as $field) {
            if ($field->rules) {
                $validation['data.'.$field->name] = $field->rules;
            }
        }

        if ($validation) {
            $this->validate($validation);
        }

        foreach ($this->fields as $field) {
            ($field->set)($this->data[$field->name]);
        }
    }
}; ?>

<x-slot:title>
    {{ __('Site settings') }}
</x-slot:title>

<x-field.group tag="form" wire:submit="submit">
    <x-field.set>
        @foreach ($this->fields as $field)
            <x-field.group>
                {{ $field }}
            </x-field.group>
        @endforeach
    </x-field.set>
    <x-field orientation="horizontal">
        <x-button type="submit">Save Changes</x-button>
    </x-field>
</x-field.group>
