<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $table     = 'merchants';
    protected $guarded   = [];
    public $incrementing = false;
    protected $keyType = 'string';
}
