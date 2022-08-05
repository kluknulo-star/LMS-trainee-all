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

<<<<<<< routes/web.php
Route::get('/', [LoginController::class, 'login'])
    ->name('main');

Route::get('/login', [LoginController::class, 'login'])
    ->name('login');

Route::get('/register', [LoginController::class, 'register'])
    ->name('register');
=======
Route::get('/', [LoginController::class, 'login']);

Route::get('/login', [LoginController::class, 'login']);

Route::get('/register', [LoginController::class, 'register']);
>>>>>>> routes/web.php

Route::post('/authenticate', [LoginController::class, 'authenticate'])
    ->name('auth');

Route::get('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

Route::prefix('users')->group(function() {

    Route::get('', [UserController::class, 'index'])
        ->name('users')
        ->middleware('auth');

    Route::post('', [UserController::class, 'store'])
        ->name('usersStore')
        ->middleware('auth');

    Route::get('/create', [UserController::class, 'create'])
        ->name('usersCreate')
        ->middleware('auth');

    Route::prefix('{id}')->group(function() {

        Route::get('/', [UserController::class, 'show'])
            ->where('id', '[0-9]+')
            ->name('usersShow')
            ->middleware('auth');

        Route::get('/edit', [UserController::class, 'edit'])
            ->where('id', '[0-9]+')
            ->name('usersEdit')
            ->middleware('auth');

        Route::patch('/', [UserController::class, 'update'])
            ->where('id', '[0-9]+')
            ->name('usersUpdate')
            ->middleware('auth');

        Route::delete('/', [UserController::class, 'destroy'])
            ->where('id', '[0-9]+')
            ->name('usersDelete')
            ->middleware('auth');

    });

});

Route::prefix('courses')->group(function() {

    Route::get('', [CourseController::class, 'index'])
        ->name('courses')
        ->middleware('auth');

    Route::get('/my', [CourseController::class, 'showOwn'])
        ->name('coursesOwn')
        ->middleware('auth');

    Route::prefix('{id}')->group(function (){

        Route::post('/assignments', [CourseController::class, 'assign'])
            ->where('id', '[0-9]+')
            ->name('coursesAssignments')
            ->middleware('auth');

        Route::get('', [CourseController::class, 'play'])
            ->where('id', '[0-9]+')
            ->name('coursesPlay')
            ->middleware('auth');

        Route::get('/edit', [CourseController::class, 'edit'])
            ->where('id', '[0-9]+')
            ->name('coursesEdit')
            ->middleware('auth');

        Route::get('/edit/assignments', [CourseController::class, 'editAssignments'])
            ->where('id', '[0-9]+')
            ->name('coursesEditAssignments')
            ->middleware('auth');

        Route::patch('', [CourseController::class, 'update'])
            ->where('id', '[0-9]+')
            ->name('coursesUpdate')
            ->middleware('auth');

        Route::delete('', [CourseController::class, 'destroy'])
            ->where('id', '[0-9]+')
            ->name('coursesDelete')
            ->middleware('auth');

    });

    Route::get('/create', [CourseController::class, 'create'])
        ->name('coursesCreate')
        ->middleware('auth');

    Route::post('', [CourseController::class, 'store'])
        ->name('coursesStore')
        ->middleware('auth');

    /**
     *
     * Мейби нужен будет но не факт
     *
     */
    Route::get('/{id}/statistics', [CourseController::class, 'statistics'])
        ->name('coursesStatistics')
        ->middleware('auth');

});


