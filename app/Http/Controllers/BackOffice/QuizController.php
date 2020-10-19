<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Quiz;
use App\Models\Student;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $merchantId = auth()->id();
        $quizzes    = Quiz::query()
            ->where(request()->only(['studentId', 'unitId']))
            ->where('merchantId', $merchantId)
            ->get();

        return $quizzes;
    }

    public function store(Request $request)
    {
        $assessment = Assessment::query()
            ->where('unitId', $request->input('unitId'))
            ->firstOrFail();

        /**
         * @var Student
         */
        $student    = Student::findOrFail($request->input('studentId'));
        $merchantId = auth()->id();

        abort_if($student != $merchantId, 400, 'El estudiante no pertenece al negocio.');

        $latest = Quiz::where(
            [
                'assessmentId' => $assessment->id,
                'studentId'    => $student->id,
                'merchantId'   => $merchantId,
            ])
            ->latest()
            ->first();

        $attempt = 1 + data_get($latest, 'attempt', 0);

        $quiz = Quiz::create(
            [
                'id'           => $request->input('id'),
                'assessmentId' => $assessment->id,
                'studentId'    => $student->id,
                'merchantId'   => $merchantId,
                'score'        => 0,
                'attempt'      => $attempt,
                'dueDate'      => now()->addMinutes($assessment->duration),
            ]
        );

        return $quiz;
    }
}
