<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Quiz;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $student    = auth()->user();
        $studentId  = $student->id;
        $merchantId = $student->merchantId;

        /**
         * @var Assessment|null
         */
        $assessment = Assessment::where('unitId', request('unitId'))->first();

        abort_if(
            is_null($assessment),
            400,
            'Assessment For UnitId not found.'
        );

        $quizzes = Quiz::query()
            ->where(compact('studentId', 'merchantId'))
            ->where('assessmentId', '=', $assessment->id)
            ->get();

        return response()->json([
            'assessment' => $assessment,
            'quizzes'    => $quizzes,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = auth()->user();
        $quiz    = Quiz::with('replies')
            ->where('studentId', $student->id)
            ->find($id);

        if (!$quiz) {
            return abort(400, 'Quiz Not Found');
        }

        $assessment = Assessment::with(
            [
                'questions' => function ($questions) {
                    $questions->with(['answers' => function ($answers) {
                        $answers->orderBy('position');
                    }]);
                },
                'unit',
            ])
            ->find($quiz->assessmentId);

        return response()->json([
            'quiz'       => $quiz,
            'assessment' => $assessment,
        ]);
    }
}
