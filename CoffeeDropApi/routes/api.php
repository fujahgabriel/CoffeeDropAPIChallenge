<?php

use Illuminate\Http\Request;

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

Route::group([
   'prefix' => 'auth'
    ], function () {
    Route::post('login', 'Auth\AuthController@login')->name('login');
    Route::post('register', 'Auth\AuthController@register');
    Route::group([
    'middleware' => 'auth:api'
    ], function() {
    Route::get('logout', 'Auth\AuthController@logout');
    Route::get('user', 'Auth\AuthController@user');   
    });

    /* return all locations*/
    Route::resource('Locations', 'Api\LocationController');

    /* store new locations*/
    Route::post('CreateNewLocation', 'Api\LocationController@CreateNewLocation');

    /* get nearestlocation by postcode */
    Route::get('GetNearestLocation/{postcode}','Api\LocationController@GetNearestLocation');

    /* calculate cashback */
    Route::post('CalculateCashback', 'Api\CashbackController@CalculateCashback');
    
});

