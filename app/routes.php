<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/** Descargos  */

Route::get('/', function()
{
	return View::make('hello');
});
Route::get('show','SolicitudeController@show');
Route::get('newSolicitude','SolicitudeController@newSolicitude');
Route::get('ruc',function(){

    return View::make('ruc');

});

Route::get('test','RucController@show');



/**   Gastos */