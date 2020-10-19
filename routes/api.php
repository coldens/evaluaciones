<?php

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('quizzes', 'Api\QuizController')->only(['index', 'show']);
    Route::apiResource('replies', 'Api\ReplyController')->only(['store']);
});
