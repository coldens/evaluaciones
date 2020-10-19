<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    protected $table     = 'students';
    protected $guarded   = [];
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = [
        'api_token',
    ];
}
