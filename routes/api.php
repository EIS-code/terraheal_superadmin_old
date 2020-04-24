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

        Route::group(['prefix' => 'room'], function () {
            Route::get('/add/{bookingInfoId}/{roomId}', 'UserController@addRoom')->name('userAddRoom');
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
            Route::get('/province', 'LocationController@getProvince')->name('getProvince');
            Route::get('/city', 'LocationController@getCity')->name('getCity');
        });
    });

    Route::group(['prefix' => 'therapist', 'namespace' => 'Therapist'], function () {
        Route::group(['prefix' => 'freelancer', 'namespace' => 'Freelancer'], function () {
            Route::post('/signup', 'FreelancerTherapistController@signup')->name('freelancerTherapistSignUp');

            Route::group(['prefix' => 'booking'], function () {
                Route::get('/list/past/{therapistId}', 'FreelancerTherapistController@getPastBooking')->name('freelancerTherapistGetPastBooking');
                Route::get('/list/future/{therapistId}', 'FreelancerTherapistController@getFutureBooking')->name('freelancerTherapistGetFutureBooking');
            });

            Route::group(['prefix' => 'massage', 'namespace' => 'Massage'], function () {
                Route::get('/start/{bookingInfoId}', 'FreelancerTherapistMassageHistoryController@startMassage')->name('freelancerTherapistStartMassage');
                Route::get('/complete/{bookingInfoId}', 'FreelancerTherapistMassageHistoryController@completeMassage')->name('freelancerTherapistCompleteMassage');
                Route::get('/pause/{bookingInfoId}', 'FreelancerTherapistMassageHistoryController@pauseMassage')->name('freelancerTherapistPauseMassage');
                Route::get('/restart/{bookingInfoId}', 'FreelancerTherapistMassageHistoryController@restartMassage')->name('freelancerTherapistRestartMassage');
            });
        });
    });

    Route::group(['prefix' => 'receptionist', 'namespace' => 'Receptionist'], function () {
        Route::post('signup', 'ReceptionistController@signup')->name('receptionistSignup');

        Route::group(['prefix' => 'booking'], function () {
            Route::get('/list/today/{shopId}', 'ReceptionistController@getTodayBooking')->name('receptionistGetTodayBooking');
            Route::get('/list/past/{shopId}', 'ReceptionistController@getPastBooking')->name('receptionistGetPastBooking');
            Route::get('/list/future/{shopId}', 'ReceptionistController@getFutureBooking')->name('receptionistGetFutureBooking');
            Route::get('/list/done/{shopId}', 'ReceptionistController@getDoneBooking')->name('receptionistGetDoneBooking');
        });
    });
});
