<?php

use App\Courses\Controllers\CourseController;
use App\Users\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Users\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LoginController::class, 'login'])->name('main')->middleware('guest');
Route::get('/', [UserController::class, 'index'])->name('main')->middleware('auth');

Route::get('/login', [LoginController::class, 'login'])->name('login')->middleware('guest');
Route::get('/register', [LoginController::class, 'register'])->name('register')->middleware('guest');
Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('authenticate')->middleware('guest');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('/users/', [UserController::class, 'store'])->name('users.store');

Route::prefix('users')->middleware('auth')->group(function() {

    Route::get('', [UserController::class, 'index'])->name('users');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');

    Route::prefix('{id}')->group(function() {
        Route::get('/', [UserController::class, 'show'])->name('users.show')->where('id', '[0-9]+');
        Route::post('/restore', [UserController::class, 'restore'])->name('users.restore')->where('id', '[0-9]+');
        Route::get('/edit', [UserController::class, 'edit'])->where('id', '[0-9]+')->name('users.edit');
        Route::patch('/', [UserController::class, 'update'])->where('id', '[0-9]+')->name('users.update');
        Route::delete('/', [UserController::class, 'destroy'])->where('id', '[0-9]+')->name('users.destroy');
    });

});

Route::prefix('courses')->middleware('auth')->group(function() {
    Route::get('', [CourseController::class, 'showAssignments'])->name('courses.assignments');
    Route::get('/my', [CourseController::class, 'showOwn'])->name('courses.own');
    Route::get('/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('', [CourseController::class, 'store'])->name('courses.store');

    Route::prefix('{id}')->group(function (){
        Route::post('/assignments', [CourseController::class, 'assign'])->where('id', '[0-9]+')->name('courses.assignments');
        Route::get('', [CourseController::class, 'play'])->where('id', '[0-9]+')->name('courses.play');
        Route::get('/edit', [CourseController::class, 'edit'])->where('id', '[0-9]+')->name('courses.edit');
        Route::get('/edit/assignments', [CourseController::class, 'editAssignments'])->where('id', '[0-9]+')->name('courses.edit.assignments');
        Route::patch('', [CourseController::class, 'update'])->where('id', '[0-9]+')->name('courses.update');
        Route::delete('', [CourseController::class, 'destroy'])->where('id', '[0-9]+')->name('courses.delete');
    });

    /**
     *
     * Мейби нужен будет но не факт
     *
     */
    Route::get('/{id}/statistics', [CourseController::class, 'statistics'])->name('courses.statistics');

});


