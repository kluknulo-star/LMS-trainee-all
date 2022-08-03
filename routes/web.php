<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function (){
    return 'show login page';
});

Route::get('/register', function (){
    return 'show register page';
});

Route::prefix('users')->group(function() {

    Route::get('', function (){
        return 'UserController index action';
    });

    Route::prefix('{id}')->group(function() {

        Route::get('/', [UserController::class, 'show'])->where('id', '[0-9]+');

        Route::get('/edit', [UserController::class, 'edit'])->where('id', '[0-9]+');

        Route::put('/', [UserController::class, 'update'])->where('id', '[0-9]+');

        Route::delete('/', [UserController::class, 'destroy'])->where('id', '[0-9]+');

    });

});

Route::prefix('courses')->group(function() {

    Route::get('', function (){
        return 'CourseController index action';
    });

    Route::get('/my', function (){
        return 'CourseController showOwn action';
    });

    Route::prefix('{id}')->group(function (){

        Route::post('/assignments', function (){
        return 'CourseController assign action';
        })->where('id', '[0-9]+');

        Route::get('/', function (){
            return 'CourseController play action';
        })->where('id', '[0-9]+');

        Route::get('/edit', function (){
            return 'CourseController edit action';
        })->where('id', '[0-9]+');

        Route::get('/edit/assignments', function (){
            return 'CourseController editAssignments action';
        })->where('id', '[0-9]+');

        Route::put('', function (){
            return 'CourseController update action';
        })->where('id', '[0-9]+');

        Route::delete('/', function (){
            return 'CourseController destroy action';
        })->where('id', '[0-9]+');

    });

    Route::get('/create', function (){
        return 'CourseController create action';
    });

    Route::post('', function (){
        return 'CourseController store action';
    });

    /**
     *
     * Мейби нужен будет но не факт
     *
     */
    Route::get('/{id}/statistics', function (){
        return 'CourseController statistics action';
    });

});


