<?php

namespace App\Console\Commands;

use App\Models\Answer;
use App\Models\Assessment;
use App\Models\Question;
use App\Models\Unit;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportAssessments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:assessments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importar evaluaciones de la anterior base de datos.';

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
        $positions = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

        $quizzes = DB::table('cursos_virtuales.quiz')
            ->join('cursos_virtuales.course_unity_lesson AS lesson', 'lesson.id', '=', 'quiz.id_lesson')
            ->join('cursos_virtuales.course_unity AS unit', 'unit.id', '=', 'lesson.id_unity')
            ->select(['quiz.*', 'unit.uuid as unitId'])
            ->get();

        $units = DB::table('cursos_virtuales.course_unity AS unit')->get();

        foreach ($units as $unit) {
            if (Assessment::where('unitId', $unit->id)->first()) {
                continue;
            }

            $unitQuizzes = $quizzes->filter(static function ($quiz) use ($unit) {
                return $quiz->unitId == $unit->id;
            });

            $questions = DB::table('cursos_virtuales.questions')
                ->whereIn('id_quiz', $unitQuizzes->pluck('id'))
                ->inRandomOrder()
                ->get()
                ->take(20);

            $answers = DB::table('cursos_virtuales.answer')
                ->whereIn('id_question', $questions->pluck('id'))
                ->get();

            if ($questions->isEmpty() || $answers->isEmpty()) {
                continue;
            }

            $assessmentId = Str::uuid()->toString();

            try {
                DB::beginTransaction();

                Assessment::create(
                    [
                        'id'       => $assessmentId,
                        'unitId'   => $unit->uuid,
                        'duration' => 45,
                    ]
                );
                Unit::create(
                    [
                        'id'       => $unit->uuid,
                        'title'    => $unit->description,
                        'path'     => $unit->description_seo,
                        'position' => $unit->sequence,
                    ]
                );

                $questions->each(static function ($imported) use ($assessmentId, $answers, $positions) {
                    $questionAnswers = $answers->where('id_question', $imported->id)->values();
                    $correct         = $questionAnswers->where('answer_correct', 1)->first();

                    $question = Question::create([
                        'id'              => $imported->uuid,
                        'assessmentId'    => $assessmentId,
                        'type'            => 'optionSelect',
                        'correctAnswerId' => $correct->uuid,
                        'content'         => $imported->title,
                    ]);

                    foreach ($questionAnswers as $key => $answer) {
                        Answer::create([
                            'id'         => $answer->uuid,
                            'questionId' => $question->id,
                            'position'   => $positions[$key],
                            'type'       => 'optionSelect',
                            'content'    => $answer->description,
                        ]);
                    }
                });

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }
}
