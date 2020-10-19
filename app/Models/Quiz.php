<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table     = 'quizzes';
    protected $guarded   = [];
    public $incrementing = false;
    protected $keyType   = 'string';

    protected $casts = [
        'dueDate'  => 'datetime:' . DATE_ATOM,
        'finished' => 'boolean',
    ];

    const MAX_VALID_ATTEMPT = 2;

    public function setAttemptAttribute($value)
    {
        $max = self::MAX_VALID_ATTEMPT;
        if ($value > $max) {
            throw new Exception("Error: <$value> excede el limite de intentos <$max>");
        }

        $this->attributes['attempt'] = $value;
    }

    public function updateScore()
    {
        $replies = $this->replies;
        $replies->loadMissing('question');

        $score = 0;

        foreach ($replies as $reply) {
            $score += $reply->getScore();
        }

        if ($this->attempt > 1) {
            $score -= 2;
        }

        $this->score = $score;
        $this->save();
    }

    public function replies()
    {
        return $this->hasMany(Reply::class, 'quizId', 'id');
    }

    public function assessment()
    {
        return $this->hasOne(Assessment::class, 'id', 'assessmentId');
    }

    public function merchant()
    {
        return $this->hasOne(Merchant::class, 'id', 'merchantId');
    }
}
