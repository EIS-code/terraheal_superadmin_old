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
