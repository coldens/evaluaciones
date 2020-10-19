<?php

namespace App\Http\Requests;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReplyStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $student  = $this->user();
        $quiz = Quiz::query()
            ->where('studentId', $student->id)
            ->where('assessmentId', $this->assessmentId)
            ->find($this->quizId);

        if ($quiz == null) {
            return abort(400, "Error: no se encontro una evaluación activa con el id: <{$this->quizId}>");
        }
        if (now()->gte($quiz->dueDate)) {
            return abort(406, 'Error: la evaluación ha expirado.');
        }

        $question = Question::query()
            ->where('assessmentId', $this->assessmentId)
            ->find($this->questionId);
        if ($question == null) {
            return abort(406, 'No se encontró una pregunta con el id ' . $this->questionId);
        }

        return [
            'id'           => ['required', 'uuid'],
            'assessmentId' => ['required', 'uuid'],
            'quizId'       => ['required', 'uuid'],
            'questionId'   => ['required', 'uuid'],
            'answerId'     => [
                Rule::requiredIf(function () {
                    return $this->type === 'optionSelect';
                }),
                Rule::exists(Answer::class, 'id')->where('questionId', $this->questionId),
            ],
            'type'         => [
                'required',
                function ($attribute, $value, $fail) use ($question) {
                    if ($question->type !== $value) {
                        $fail("Error: <$attribute> debe ser igual a <{$question->type}>");
                    }
                },
            ],
            'content'      => ['nullable', 'string'],
        ];
    }
}
