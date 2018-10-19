<?php

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
    return view('index',  ["APP_HOST" => env("APP_HOST"), "APP_PORT" => env("APP_PORT") ,"CONEKTA_API_PUBLIC_KEY" => env("CONEKTA_API_PUBLIC_KEY")] );
});

Route::get("/google257379cc5af56db5.html", function(){
    View::addExtension('html', 'php');
    return view('google257379cc5af56db5', []);
});

Route::get('/todosxuno/', "api\\v1\campaign\Todosxuno@index")->name('todosxuno');
Route::post('/campaign/todosxuno/counter', "api\\v1\campaign\Todosxuno@counter");
Route::get('/sharefb/', function(){
   return view("/includes/campaigns/todosxuno/sharefb", ["APP_HOST" => env("APP_HOST"), "APP_PORT" => env("APP_PORT") ,"CONEKTA_API_PUBLIC_KEY" => env("CONEKTA_API_PUBLIC_KEY")] );
});

Route::get('/todosxuno/captura', function () {
    return view('includes/campaigns/todosxuno/captura',  ["APP_HOST" => env("APP_HOST"), "APP_PORT" => env("APP_PORT")] );
});

Route::get('/reporte/suscriptorcausas/', 'report\\SubscriberCausesController@index');
Route::get('/reporte/suscriptorcausas/descargar', 'report\\SubscriberCausesController@download');
Route::get('/reporte/suscriptores/', 'report\\SubscriberController@index');
Route::get('/reporte/suscriptores/descargar', 'report\\SubscriberController@download');
Route::get('/reporte/suscriptores/sync', 'report\\SubscriberController@conekta');
Route::get('/reporte/montos/', 'report\\AmountsController@index');
Route::get('/reporte/montos/descargar', 'report\\AmountsController@download');
Route::get('/reporte/suscriptores/updateamounts', 'report\\SubscriberController@updateAmounts');
Route::get('/reporte/suscriptores/getall', 'report\\SubscriberController@getall');
Route::get('/mail/send', 'MailController@send');
Route::get('/conekta', 'report\\SubscriberController@conekta');
Route::get('/conekta/sync', 'ConektaSyncController@sync');
Route::get('/mail', function(){
    return view("email/subscription");
});


Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/dashboard', 'DashboardController@index');
});
