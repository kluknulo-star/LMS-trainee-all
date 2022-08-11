<?php

use App\Courses\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Courses Routes
|--------------------------------------------------------------------------
|
| Here is where you can register courses routes for your application.
|
*/

Route::prefix('courses')->middleware('auth')->group(function() {
    Route::get('', [CourseController::class, 'showAssignments'])->name('courses.assignments');
    Route::get('/my', [CourseController::class, 'showOwn'])->name('courses.own');
    Route::get('/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('', [CourseController::class, 'store'])->name('courses.store');

    Route::prefix('{id}')->group(function (){
        Route::post('/assignments', [CourseController::class, 'assign'])->where('id', '[0-9]+')->name('courses.course.assginments');
        Route::get('', [CourseController::class, 'play'])->where('id', '[0-9]+')->name('courses.play');
        Route::get('/edit', [CourseController::class, 'edit'])->where('id', '[0-9]+')->name('courses.edit');
        Route::get('/edit/assignments', [CourseController::class, 'editAssignments'])->where('id', '[0-9]+')->name('courses.edit.assignments');
        Route::patch('', [CourseController::class, 'update'])->where('id', '[0-9]+')->name('courses.update');
        Route::delete('', [CourseController::class, 'destroy'])->where('id', '[0-9]+')->name('courses.delete');
        Route::post('/restore', [CourseController::class, 'restore'])->where('id', '[0-9]+')->name('courses.restore');
    });

    /**
     *
     * Мейби нужен будет но не факт
     *
     */
    Route::get('/{id}/statistics', [CourseController::class, 'statistics'])->name('courses.statistics');

});
