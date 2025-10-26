<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Option extends Model
{
    protected $table = 'options';

    protected $fillable = ['name', 'value', 'autoload'];

    public $timestamps = false;

    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Str::isJson($value) ? json_decode($value, true) : $value,
            set: fn ($value) => is_array($value) ? json_encode($value) : $value,
        );
    }
}
