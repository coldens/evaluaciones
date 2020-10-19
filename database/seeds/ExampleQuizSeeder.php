<?php

use App\Models\Assessment;
use App\Models\Quiz;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExampleQuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $student    = Student::first();
        $assessment = Assessment::first();

        Quiz::create([
            'id'           => Str::uuid()->toString(),
            'studentId'    => $student->id,
            'assessmentId' => $assessment->id,
            'merchantId'   => $student->merchantId,
            'attempt'      => 1,
            'score'        => 0,
            'dueDate'      => now()->addMinutes($assessment->duration)
        ]);
    }
}
