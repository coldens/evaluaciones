<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $table     = 'assessments';
    protected $guarded   = [];
    public $incrementing = false;
    protected $keyType   = 'string';

    public function questions()
    {
        return $this->hasMany(Question::class, 'assessmentId', 'id');
    }

    public function unit()
    {
        return $this->hasOne(Unit::class, 'id', 'unitId');
    }
}
