<?php

use App\Courses\Controllers\CourseContentController;
use App\Courses\Controllers\CourseController;
use App\Courses\Controllers\ExportCourseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Courses Routes
|--------------------------------------------------------------------------
|
| Here is where you can register courses routes for your application.
|
*/

Route::get('/export/{type}', [ExportCourseController::class, 'export'])->name('courses.export');
Route::post('/export/download/{id}', [ExportCourseController::class, 'exportDownload'])->name('courses.export.download');

Route::prefix('courses')->middleware(['auth', 'confirmed'])->group(function() {
    Route::get('', [CourseController::class, 'showAssignedCourses'])->name('courses.assignments');
    Route::get('/my', [CourseController::class, 'showOwnCourses'])->name('courses.own');
    Route::get('/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('', [CourseController::class, 'store'])->name('courses.store');


    Route::prefix('{id}')->group(function (){
        Route::post('/assign', [CourseController::class, 'assign'])->where('id', '[0-9]+')->name('courses.course.assgin');
        Route::post('/deduct', [CourseController::class, 'deduct'])->where('id', '[0-9]+')->name('courses.course.deduct');
        Route::get('', [CourseController::class, 'play'])->where('id', '[0-9]+')->name('courses.play');
        Route::patch('', [CourseController::class, 'update'])->where('id', '[0-9]+')->name('courses.update');
        Route::delete('', [CourseController::class, 'destroy'])->where('id', '[0-9]+')->name('courses.delete');
        Route::post('/restore', [CourseController::class, 'restore'])->where('id', '[0-9]+')->name('courses.restore');
        Route::get('/edit', [CourseController::class, 'edit'])->where('id', '[0-9]+')->name('courses.edit');
        Route::get('/edit/assignments/{state}', [CourseController::class, 'editAssignments'])->where('id', '[0-9]+')->name('courses.edit.assignments');
        Route::get('/statistics', [CourseController::class, 'statistics'])->name('courses.statistics');

        Route::prefix('section')->group(function() {
            Route::post('', [CourseContentController::class, 'store'])->name('courses.create.section');

            Route::prefix('{section_id}')->group(function() {
                Route::get('/edit', [CourseContentController::class, 'edit'])->name('courses.edit.section');
                Route::patch('/restore', [CourseContentController::class, 'restore'])->name('courses.restore.section');
                Route::patch('', [CourseContentController::class, 'update'])->name('courses.update.section');
                Route::delete('', [CourseContentController::class, 'destroy'])->name('courses.destroy.section');
            });
        });

    });
});
