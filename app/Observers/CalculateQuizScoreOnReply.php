<?php

namespace App\Observers;

use App\Jobs\RecalculateQuizScore;
use App\Models\Quiz;
use App\Models\Reply;

class CalculateQuizScoreOnReply
{
    /**
     * Handle the reply "created" event.
     *
     * @param  \App\Models\Reply  $reply
     * @return void
     */
    public function created(Reply $reply)
    {
        $this->saveQuizScore($reply);
    }

    /**
     * Handle the reply "updated" event.
     *
     * @param  \App\Models\Reply  $reply
     * @return void
     */
    public function updated(Reply $reply)
    {
        $this->saveQuizScore($reply);
    }

    private function saveQuizScore(Reply $reply)
    {
        RecalculateQuizScore::dispatch(Quiz::query()->find($reply->quizId));
    }
}
