<?php


use Illuminate\Support\Facades\Route;

Route::prefix('quizzes/{quiz}')->group(function() {
    Route::get('', [QuizController::class, 'retrieveQuiz'])->name('quiz.retrieve');
    Route::get('/play', [QuizController::class, 'play'])->name('quiz.play');
    Route::post('', [QuizController::class, 'storeResults'])->name('quiz.results.store');
    Route::get('/my-results', [QuizController::class, 'showResults'])->name('quiz.results.show');
    Route::post('/my-results', [QuizController::class, 'retrieveResults'])->name('quiz.results.retrieve');

    Route::prefix('questions')->group(function() {
        Route::get('', [QuizController::class, 'showQuestions'])->name('quiz.questions.show');
        Route::post('', [QuizController::class, 'storeQuestions'])->name('quiz.questions.store');
        Route::delete('', [QuizController::class, 'deleteQuestion'])->name('quiz.questions.delete');

        Route::prefix('{question}')->group(function() {
            Route::get('', [QuizController::class, 'showOptions'])->name('quiz.options.show');
            Route::post('', [QuizController::class, 'storeOptions'])->name('quiz.options.store');
        });
    });
});
