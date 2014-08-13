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
Route::get('nueva-solicitud','Dmkt\SolicitudeController@newSolicitude');
Route::get('prueba','Dmkt\SolicitudeController@test');
Route::get('ver-solicitud','Dmkt\SolicitudeController@viewSolicitude');
Route::get('nueva-actividad','Dmkt\ActivityController@newActivity');
Route::get('getclients','Dmkt\SolicitudeController@getClients');
Route::post('registrar-solicitud','Dmkt\SolicitudeController@registerSolicitude');
Route::get('listar-solicitudes/{id}','Dmkt\SolicitudeController@listSolicitude');

Route::get('test2','Dmkt\SolicitudeController@test');

// ======================================================================

/**   Gastos */

Route::get('registrar-gasto','Expense\ExpenseController@show');
Route::get('test','Expense\RucController@show');
Route::post('consultarRuc','Expense\RucController@show');
// Route::get('ruc',function(){
//     return View::make('Expense\ruc');
// });
