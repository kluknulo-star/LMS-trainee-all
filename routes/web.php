<?php

use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\SocialController;
use App\Users\Controllers\LoginController;
use App\Users\Controllers\UserController;
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

Route::get('/', [LoginController::class, 'login'])->name('main')->middleware('guest');
Route::get('/', [UserController::class, 'index'])->name('main')->middleware('auth.admin');

Route::get('/login', [LoginController::class, 'login'])->name('login')->middleware('guest');
Route::get('/register', [LoginController::class, 'register'])->name('register')->middleware('guest');
Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('authenticate')->middleware('guest');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::group(['middleware' => 'guest'], function () {
    Route::get('/vk/auth', [SocialController::class, 'index'])->name('vk.auth');
    Route::get('/vk/auth/callback', [SocialController::class, 'callBack']);
});

Route::get('/change-lang', [LocalizationController::class, 'changeLanguage'])->name('change.language');

Route::get('/test', function (){
    $lrs = new TinCan\RemoteLRS();
    $lrs->setEndpoint('http://127.0.0.1:8001/api/statements');



    $actor = new TinCan\Agent(
        [ 'mbox' => 'mailto:info@tincanapi.com' ]
    );
    $verb = new TinCan\Verb(
        [ 'id' => 'http://adlnet.gov/expapi/verbs/experienced' ]
    );
    $activity = new TinCan\Activity(
        [ 'id' => 'http://rusticisoftware.github.com/TinCanPHP' ]
    );
    $statement = new TinCan\Statement(
        [
            'actor' => $actor,
            'verb'  => $verb,
            'object' => $activity,
        ]
    );

    $saveResponse = $lrs->saveState(
        $activity,
        $actor,
        'testDocument',
        'someValue'
    );

    dump($saveResponse);
//    $retriveResponse = $lrs->retrieveStatement(1);

//    dump($retriveResponse);
});

