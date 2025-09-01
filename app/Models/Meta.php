<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    protected $table = 'meta';

    protected $fillable = ['key', 'value'];

    public $timestamps = false;
}
