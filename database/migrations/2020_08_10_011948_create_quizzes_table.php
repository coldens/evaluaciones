<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->string('studentId')->index();
            $table->string('assessmentId')->index();
            $table->string('merchantId')->index();

            $table->unsignedTinyInteger('attempt');
            $table->unsignedTinyInteger('score')->default(0);
            $table->dateTime('dueDate');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quizzes');
    }
}
