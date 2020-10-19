<?php

namespace App\Console\Commands\Schedule;

use App\Models\Quiz;
use Illuminate\Console\Command;

class FinishExpiredQuizzes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:finish-expired-quizzes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $quizzes = Quiz::where('finished', '=', false)
            ->where('dueDate', '<=', now())
            ->cursor();

        // actualiza los quizzes a finished = true y dispara los eventos.
        $quizzes->each(static function (Quiz $quiz) {
            $quiz->finished = true;
            $quiz->save();
        });
    }
}
