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
Route::get('show','Dmkt\SolicitudeController@show');
Route::get('newSolicitude','Dmkt\SolicitudeController@newSolicitude');
Route::get('ruc',function(){

    return View::make('ruc');

});
Route::get('prueba','Dmkt\SolicitudeController@test');
Route::get('test','RucController@show');



/**   Gastos */
