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

Route::group(['middleware' => ['web.auth.api']], function () {
    Route::get('/error', 'ErrorController@error')->name('error');

    Route::group(['prefix' => 'user', 'namespace' => 'User'], function () {
        Route::group(['prefix' => 'signup'], function () {
            Route::post('/', 'UserController@signUp')->name('userSignup');
            // Route::get('/facebook', 'FacebookController@signupFacebook')->name('signupFacebook');
            Route::get('/facebook/redirect', 'FacebookController@redirect')->name('redirectFacebookSignup');
            Route::get('/facebook/callback', 'FacebookController@callback')->name('callbackFacebookSignup');

            Route::get('/google/redirect', 'GoogleController@redirect')->name('redirectGoogleSignup');
            Route::get('/google/callback', 'GoogleController@callback')->name('callbackGoogleSignup');

            // Route::get('/google/callback', 'GoogleController@callback')->name('callbackGoogleSignup');

            Route::get('/email/sendOtp/{email}', 'UserController@sendOtpEmail')->name('sendOtpEmail');
            Route::get('/sms/sendOtp/{number}', 'UserController@sendOtpSms')->name('sendOtpSms');
        });

        Route::post('/signin', 'UserController@signIn')->name('userSignIn');
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
        Route::group(['prefix' => 'freelancer'], function () {
            Route::post('/signup', 'TherapistController@signup')->name('freelancerTherapistSignUp');
            Route::post('/signin', 'TherapistController@signIn')->name('freelancerTherapistSignIn');
            Route::post('/update/{therapistId}', 'TherapistController@update')->name('freelancerTherapistUpdate');

            Route::group(['prefix' => 'booking'], function () {
                Route::get('/list/past/{therapistId}', 'TherapistController@getPastBooking')->name('freelancerTherapistGetPastBooking');
                Route::get('/list/future/{therapistId}', 'TherapistController@getFutureBooking')->name('freelancerTherapistGetFutureBooking');
            });

            Route::group(['prefix' => 'massage', 'namespace' => 'Massage'], function () {
                Route::get('/start/{bookingInfoId}', 'TherapistMassageHistoryController@startMassage')->name('freelancerTherapistStartMassage');
                Route::get('/complete/{bookingInfoId}', 'TherapistMassageHistoryController@completeMassage')->name('freelancerTherapistCompleteMassage');
                Route::get('/pause/{bookingInfoId}', 'TherapistMassageHistoryController@pauseMassage')->name('freelancerTherapistPauseMassage');
                Route::get('/restart/{bookingInfoId}', 'TherapistMassageHistoryController@restartMassage')->name('freelancerTherapistRestartMassage');
            });

            Route::group(['prefix' => 'calendar'], function () {
                Route::post('/create', 'TherapistCalendarController@createCalendar')->name('freelancerTherapistCreateCalendar');
                Route::post('/updateTime/{therapistId}/{date}', 'TherapistCalendarController@updateTimeCalendar')->name('freelancerTherapistUpdateTimeCalendar');
                Route::get('/delete/{therapistId}/{date}', 'TherapistCalendarController@deleteCalendar')->name('freelancerTherapistDeleteCalendar');
            });

            Route::get('/search', 'TherapistController@search')->name('freelancerTherapistSearch');

            Route::group(['prefix' => 'review'], function () {
                Route::post('/create', 'TherapistReviewController@create')->name('freelancerTherapistReviewCreate');
            });
        });

        Route::post('/signup', 'TherapistController@signup')->name('therapistSignUp');
        Route::post('/signin', 'TherapistController@signIn')->name('therapistSignIn');
        Route::post('/update/{therapistId}', 'TherapistController@update')->name('therapistUpdate');
        Route::group(['prefix' => 'booking'], function () {
            Route::get('/list/past/{therapistId}', 'TherapistController@getPastBooking')->name('therapistGetPastBooking');
            Route::get('/list/future/{therapistId}', 'TherapistController@getFutureBooking')->name('therapistGetFutureBooking');
        });
        Route::group(['prefix' => 'calendar'], function () {
            Route::post('/create', 'TherapistCalendarController@createCalendar')->name('therapistCreateCalendar');
            Route::post('/updateTime/{therapistId}/{date}', 'TherapistCalendarController@updateTimeCalendar')->name('therapistUpdateTimeCalendar');
            Route::get('/delete/{therapistId}/{date}', 'TherapistCalendarController@deleteCalendar')->name('therapistDeleteCalendar');
            Route::post('/absent', 'TherapistCalendarController@absentCalendar')->name('therapistAbsentCalendar');
        });

        Route::group(['prefix' => 'language'], function () {
            Route::post('/add', 'TherapistLanguageController@addLanguage')->name('therapistAddLanguage');
        });

        Route::group(['prefix' => 'verify'], function () {
            Route::post('/mobile/{therapistId}', 'TherapistController@verifyMobile')->name('therapistVerifyMobile');
            Route::post('/email/{therapistId}', 'TherapistController@verifyEmail')->name('therapistVerifyEmail');
        });
        Route::group(['prefix' => 'compare'], function () {
            Route::post('/otp/email/{therapistId}', 'TherapistController@compareOtp')->name('therapistCompareOtp');
        });

        Route::group(['prefix' => 'documents/{therapistId}'], function () {
            Route::post('/', 'TherapistDocumentController@create')->name('therapistDocumentCreate');
        });

        Route::group(['prefix' => 'profile'], function () {
            Route::post('update/{therapistId}', 'TherapistController@updateProfile')->name('therapistProfileUpdate');
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

    Route::group(['prefix' => 'staff', 'namespace' => 'Staff'], function () {
        Route::post('signup', 'StaffController@signup')->name('staffSignup');
        Route::post('update/{staffId}', 'StaffController@update')->name('staffUpdate');

        Route::group(['prefix' => 'attendance'], function () {
            Route::post('in/{staffId}', 'StaffAttendanceController@attendanceIn')->name('staffAttendanceIn');
            Route::post('breakIn/{staffId}', 'StaffAttendanceController@attendanceBreakIn')->name('staffAttendanceBreakIn');
            Route::post('breakOut/{staffId}', 'StaffAttendanceController@attendanceBreakOut')->name('staffAttendanceBreakOut');
            Route::post('out/{staffId}', 'StaffAttendanceController@attendanceOut')->name('staffAttendanceOut');
        });
    });

    Route::group(['prefix' => 'massage', 'namespace' => 'Massage'], function () {
        Route::post('get', 'MassageController@get')->name('massageGet');
    });
});
