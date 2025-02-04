<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table     = 'answers';
    protected $guarded   = [];
    public $incrementing = false;
    protected $keyType = 'string';
}
