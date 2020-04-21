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

        Route::get('/getDetails/{id}', 'UserController@getDetails')->name('userGetDetails');
        Route::post('/update/{id}', 'UserController@update')->name('userUpdate');

        Route::group(['prefix' => 'booking'], function () {
            Route::post('/create', 'UserController@bookingCreate')->name('userBooking');
            Route::post('/update/{bookingInfoId}', 'UserController@bookingUpdate')->name('userBookingUpdate');
            Route::get('/list/past/{userId}', 'UserController@getPastBooking')->name('userGetPastBooking');
            Route::get('/list/future/{userId}', 'UserController@getFutureBooking')->name('userGetFutureBooking');
        });

        Route::group(['prefix' => 'review'], function () {
            Route::get('/create/{userId}/{rating}', 'UserController@reviewCreate')->name('userReview');
            Route::get('/delete/{reviewId}', 'UserController@reviewDelete')->name('userReviewDelete');
        });

        Route::group(['prefix' => 'payment', 'namespace' => 'Payment'], function () {
            Route::post('/{bookingId}', 'PaymentController@pay')->name('userPay');
        });
    });

    Route::group(['prefix' => 'location', 'namespace' => 'Location'], function () {
        Route::group(['prefix' => 'get'], function () {
            Route::get('/country', 'LocationController@getCountry')->name('getCountry');
            Route::get('/city', 'LocationController@getCity')->name('getCity');
        });
    });
});
