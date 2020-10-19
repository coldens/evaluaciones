<?php

namespace App\Observers;

use App\Jobs\RecalculateQuizScore;
use App\Models\Quiz;

class CalculateQuizScoreOnFinished
{
    public function updated(Quiz $quiz)
    {
        if ($quiz->finished) {
            RecalculateQuizScore::dispatch($quiz);
        }
    }
}
