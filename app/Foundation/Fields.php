<?php

namespace App\Foundation;

use App\Facades\Fields as FacadesFields;

class Fields
{
    protected array $fields = [];

    public array $locations = [];

    public function fields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function location(string $type, string $location, string $operator = '='): self
    {
        $this->locations[] = [$type, $operator, $location];

        return $this;
    }

    public function register(): void
    {
        FacadesFields::register($this);
    }

    public function render($model = false, $live = false)
    {
        return view('components.fields.fields', [
            'fields' => $this->fields,
            'model' => $model,
            'live' => $live,
        ]);
    }
}
