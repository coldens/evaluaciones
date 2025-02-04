<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table     = 'units';
    protected $guarded   = [];
    public $incrementing = false;
    protected $keyType   = 'string';
}
