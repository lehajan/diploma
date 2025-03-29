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
    Route::post('verifyCode', 'App\Http\Controllers\RegistrationController@verifyCode');
    Route::post('reg', 'App\Http\Controllers\RegistrationController@reg');
    Route::get('index', 'App\Http\Controllers\MainController@index');
    Route::get('preview', 'App\Http\Controllers\RealtyController@preview');
    Route::get('realty/filter', 'App\Http\Controllers\RealtyController@filter');
    Route::get('show/{realty}', 'App\Http\Controllers\MainController@show');
});

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('user/profile', 'App\Http\Controllers\UserController@profile');
    Route::delete('user/delete', 'App\Http\Controllers\UserController@delete');
    Route::patch('user/updateProfile', 'App\Http\Controllers\UserController@updateProfile');
    Route::patch('user/updatePassword', 'App\Http\Controllers\UserController@updatePassword');

    Route::post('realty/store', 'App\Http\Controllers\RealtyController@store');
    Route::delete('realty/delete/{realty}', 'App\Http\Controllers\RealtyController@delete');
    Route::patch('realty/update/{realty}', 'App\Http\Controllers\RealtyController@update');

    Route::post('favorite/addToFavorite/{realty}', 'App\Http\Controllers\FavoriteController@addToFavorite');
    Route::delete('favorite/destroy/{realty}', 'App\Http\Controllers\FavoriteController@destroy');
    Route::get('favorite/show', 'App\Http\Controllers\FavoriteController@show');

    Route::post('feedback/create', 'App\Http\Controllers\FeedbackController@create');
    Route::delete('feedback/delete/{feedback}', 'App\Http\Controllers\FeedbackController@delete');

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

