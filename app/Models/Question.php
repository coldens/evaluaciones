<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table     = 'questions';
    protected $guarded   = [];
    public $incrementing = false;
    protected $keyType = 'string';
    protected $hidden = ['writtenAnswer', 'correctAnswerId'];

    const VALID_TYPES = ['writtenAnswer', 'optionSelect'];

    public function setTypeAttribute($value)
    {
        if (!in_array($value, self::VALID_TYPES, true)) {
            throw new Exception("Error: <$value> no es un tipo valido de pregunta");
        }

        $this->attributes['type'] = $value;
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'questionId', 'id');
    }
}
