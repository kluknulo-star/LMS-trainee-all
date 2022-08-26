<?php

use App\Courses\Controllers\CourseController;
use App\Users\Controllers\LoginController;
use App\Users\Controllers\UserEmailConfirmationController;
use Illuminate\Support\Facades\Route;
use App\Users\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
|
| Here is where you can register users routes for your application.
|
*/

Route::post('/users', [UserController::class, 'store'])->name('users.store');

Route::prefix('users')->middleware(['auth.admin', 'confirmed'])->group(function() {

    Route::get('', [UserController::class, 'index'])->name('users');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');

    Route::prefix('{id}')->group(function() {
        Route::patch('/assign-teacher', [UserController::class, 'assignTeacher'])->where('id', '[0-9]+')->name('users.assign.teacher');
        Route::post('/restore', [UserController::class, 'restore'])->where('id', '[0-9]+')->name('users.restore');
        Route::delete('/', [UserController::class, 'destroy'])->where('id', '[0-9]+')->name('users.delete');
    });

});

Route::prefix('users/{id}')->middleware(['auth', 'confirmed'])->group(function() {
        Route::get('/', [UserController::class, 'show'])->name('users.show')->where('id', '[0-9]+');
        Route::get('/edit', [UserController::class, 'edit'])->where('id', '[0-9]+')->name('users.edit');
        Route::patch('/', [UserController::class, 'update'])->where('id', '[0-9]+')->name('users.update');
        Route::get('/avatar', [UserController::class, 'editAvatar'])->where('id', '[0-9]+')->name('users.edit.avatar');
        Route::patch('/avatar', [UserController::class, 'updateAvatar'])->where('id', '[0-9]+')->name('users.update.avatar');
});
