<?php

namespace App\Foundation;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class FieldsManager
{
    protected Collection $fields;

    public function __construct()
    {
        $this->fields = collect();
    }

    public static function make(): Fields
    {
        return new Fields;
    }

    public function register(Fields $fields): void
    {
        $this->fields[] = $fields;
    }

    public function get($type, $location, $model, $live): ?HtmlString
    {
        $fieldsGroups = $this->fields->filter(
            // TODO: Proper filtering
            fn ($field) => collect($field->locations)->contains([$type, '=', $location])
        );

        $html = null;

        foreach ($fieldsGroups as $fields) {
            $html .= $fields->render($model, $live);
        }

        return $html ? new HtmlString($html) : null;
    }
}
