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

    Route::resources(['clients' => 'ClientController']);

    Route::resources(['therapists' => 'ClientController']);

    Route::resources(['bookings' => 'ClientController']);

    Route::resources(['services' => 'ClientController']);

    Route::resources(['massages' => 'ClientController']);

    Route::resources(['settings' => 'ClientController']);
});
