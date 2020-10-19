<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $table     = 'replies';
    protected $guarded   = [];
    public $incrementing = false;
    protected $keyType   = 'string';

    const VALID_TYPES = ['writtenAnswer', 'optionSelect'];

    public function getScore()
    {
        if ($this->type === 'optionSelect') {
            return $this->answerId === $this->question->correctAnswerId ? 1 : 0;
        }
        if ($this->type === 'writtenAnswer') {
            return $this->content === $this->question->writtenAnswer ? 1 : 0;
        }

        return 0;
    }

    public function setTypeAttribute($value)
    {
        if (!in_array($value, self::VALID_TYPES, true)) {
            throw new Exception("Error: <$value> no es un tipo valido de respuesta");
        }

        $this->attributes['type'] = $value;
    }

    public function question()
    {
        return $this->hasOne(Question::class, 'id', 'questionId');
    }

    /**
     * Guardar Respuesta
     *
     * @param array $data
     * @return Reply
     */
    public static function create(array $data)
    {
        switch ($data['type']) {
            case 'optionSelect':
                return self::createOptionSelect($data);
                break;
            case 'writtenAnswer':
                return self::createWrittenAnswer($data);
                break;
        }
    }

    /**
     * Guardar respuesta de seleccion simple
     *
     * @param array $data
     * @return Reply
     */
    private static function createOptionSelect(array $data)
    {
        return self::query()->updateOrCreate(
            [
                'questionId' => $data['questionId'],
                'quizId'     => $data['quizId'],
            ],
            [
                'id'       => $data['id'],
                'answerId' => $data['answerId'],
                'type'     => $data['type'],
                'content'  => null,
            ]
        );
    }

    private static function createWrittenAnswer(array $data)
    {
        return self::query()->updateOrCreate(
            [
                'questionId' => $data['questionId'],
                'quizId'     => $data['quizId'],
            ],
            [
                'id'       => $data['id'],
                'answerId' => null,
                'type'     => $data['type'],
                'content'  => $data['content'],
            ]
        );
    }
}
