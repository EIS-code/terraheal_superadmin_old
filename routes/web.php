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

Route::get('/paypal', 'ShopPaymentDetailsController@payWithPaypal');

Route::group(['prefix' => 'web'], function () {
    Route::group(['prefix' => 'user', 'namespace' => 'User'], function () {
        Route::get('/signup/facebook', 'UserController@signupFacebook')->name('signupFacebook');
        Route::get('/signup/google', 'UserController@signupGoogle')->name('signupGoogle');
    });
});

Route::group(['prefix' => 'superadmin', 'namespace' => 'Superadmin', 'middleware' => 'web'], function () {
    Auth::routes(['register' => false, 'reset' => false]);

    Route::get('/', 'SuperadminController@index')->name('superadmin.dashboard');

    Route::resources(['centers' => 'CenterController']);
    Route::post('centers/location/create', 'CenterController@locationCreate')->name('centers.location.create');
    Route::post('centers/timing/create', 'CenterController@locationCreate')->name('centers.timing.create');
    Route::post('centers/company/create', 'CenterController@companyCreate')->name('centers.company.create');

    Route::resources(['clients' => 'ClientController']);

    Route::resources(['therapists' => 'TherapistController']);

    Route::resources(['bookings' => 'BookingController']);

    Route::resources(['services' => 'ServiceController']);

    Route::resources(['messages' => 'MessageController']);

    Route::resources(['settings' => 'SettingController']);
});
