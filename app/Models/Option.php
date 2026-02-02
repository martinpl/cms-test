<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table = 'options';

    protected $fillable = ['name', 'value', 'autoload'];

    protected $casts = [
        'value' => 'array',
    ];

    public $timestamps = false;
}
