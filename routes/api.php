<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'check.guest'], function () {
    Route::post('sendVerificationCode', 'App\Http\Controllers\RegistrationController@sendVerificationCode');
    Route::post('reg', 'App\Http\Controllers\RegistrationController@reg');
//    Route::post('enterCode', 'App\Http\Controllers\RegistrationController@enterCode');

});

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('user/profile', 'App\Http\Controllers\UserController@profile');
    Route::delete('user/delete', 'App\Http\Controllers\UserController@delete');
    Route::patch('user/update', 'App\Http\Controllers\UserController@update');

//    Route::post('advertisement/create', 'App\Http\Controllers\AdvertisementController@create');
    Route::post('feedback/create', 'App\Http\Controllers\FeedbackController@create');
});

//Route::post('sendVerificationCode', 'App\Http\Controllers\RegistrationController@sendVerificationCode');
//Route::post('reg', 'App\Http\Controllers\RegistrationController@reg');


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
});

//Route::post('sendVerificationCode', 'App\Http\Controllers\RegistrationController@sendVerificationCode');
//Route::post('reg', 'App\Http\Controllers\RegistrationController@reg');
//Route::post('/verify-code', 'App\Http\Controllers\VerificationController@verify');

