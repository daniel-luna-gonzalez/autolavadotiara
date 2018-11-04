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
//Route::resource('BillingPlan', 'api\\v1\paypal\BillingPlan');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/v1/test', function(Request $request) {
    die("api v1 tiara autolavado");
});

Route::group(['middleware' => 'cors'], function() {
    Route::post('/v1/conekta/suscripcionTarjeta/create', "api\\v1\conekta\SuscripcionTarjeta@create");
    Route::post('/v1/contacto', "api\\v1\ContactoController@index");
    Route::post('/v1/conekta/creditCardPayment', "api\\v1\conekta\CreditCardPayment@create");

    Route::post('/v1/donors/index', "api\\v1\\DonorsController@index");
});

