<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::group(['middleware' => ['web']], function () {
    Route::group(['prefix' => 'user', 'namespace' => 'User'], function () {
        Route::group(['prefix' => 'signup'], function () {
            Route::post('/', 'UserController@signup')->name('userSignup');
            // Route::get('/facebook', 'FacebookController@signupFacebook')->name('signupFacebook');
            Route::get('/facebook/redirect', 'FacebookController@redirect')->name('redirectFacebookSignup');
            Route::get('/facebook/callback', 'FacebookController@callback')->name('callbackFacebookSignup');

            Route::get('/google/redirect', 'GoogleController@redirect')->name('redirectGoogleSignup');
            Route::get('/google/callback', 'GoogleController@callback')->name('callbackGoogleSignup');

            Route::get('/google/callback', 'GoogleController@callback')->name('callbackGoogleSignup');

            Route::get('/email/sendOtp/{email}', 'UserController@sendOtpEmail')->name('sendOtpEmail');
            Route::get('/sms/sendOtp/{number}', 'UserController@sendOtpSms')->name('sendOtpSms');
        });

        Route::post('/update/{id}', 'UserController@update')->name('userUpdate');
    });
});
