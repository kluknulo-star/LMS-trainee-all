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

Route::get('/', [UserController::class, 'login']);

Route::get('/login', [UserController::class, 'login']);

Route::post('/authenticate', [LoginController::class, 'authenticate']);

Route::get('/register', [UserController::class, 'register']);

Route::prefix('users')->group(function() {

    Route::get('', [UserController::class, 'index']);

    Route::post('', [UserController::class, 'store']);

    Route::get('/create', [UserController::class, 'create']);

    Route::prefix('{id}')->group(function() {

        Route::get('/', [UserController::class, 'show'])->where('id', '[0-9]+');

        Route::get('/edit', [UserController::class, 'edit'])->where('id', '[0-9]+');

        Route::patch('/', [UserController::class, 'update'])->where('id', '[0-9]+');

        Route::delete('/', [UserController::class, 'destroy'])->where('id', '[0-9]+');

    });

});

Route::prefix('courses')->group(function() {

    Route::get('', [CourseController::class, 'index']);

    Route::get('/my', [CourseController::class, 'showOwn']);

    Route::prefix('{id}')->group(function (){

        Route::post('/assignments', [CourseController::class, 'assign'])->where('id', '[0-9]+');

        Route::get('', [CourseController::class, 'play'])->where('id', '[0-9]+');

        Route::get('/edit', [CourseController::class, 'edit'])->where('id', '[0-9]+');

        Route::get('/edit/assignments', [CourseController::class, 'editAssignments'])->where('id', '[0-9]+');

        Route::put('', [CourseController::class, 'update'])->where('id', '[0-9]+');

        Route::delete('', [CourseController::class, 'destroy'])->where('id', '[0-9]+');

    });

    Route::get('/create', [CourseController::class, 'create']);

    Route::post('', [CourseController::class, 'store']);

    /**
     *
     * Мейби нужен будет но не факт
     *
     */
    Route::get('/{id}/statistics', [CourseController::class, 'statistics']);

});


