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

/** Rep. Medico */
Route::get('show_rm','Dmkt\SolicitudeController@show_rm');
Route::get('nueva-solicitud','Dmkt\SolicitudeController@newSolicitude');
Route::get('prueba','Dmkt\SolicitudeController@test');
Route::get('ver-solicitud/{id}','Dmkt\SolicitudeController@viewSolicitude');
Route::get('editar-solicitud/{id}','Dmkt\SolicitudeController@editSolicitude');
Route::post('editar-solicitud','Dmkt\SolicitudeController@formEditSolicitude');
Route::get('getclients','Dmkt\SolicitudeController@getClients');
Route::post('registrar-solicitud','Dmkt\SolicitudeController@registerSolicitude');
Route::get('listar-solicitudes/{id}','Dmkt\SolicitudeController@listSolicitude');
Route::get('getsubtypeactivities/{id}','Dmkt\SolicitudeController@subtypeactivity');
Route::get('nueva-actividad','Dmkt\ActivityController@newActivity');
Route::post('buscar-solicitudes','Dmkt\SolicitudeController@searchSolicituds');
/*activities*/
Route::get('listar-actividades-rm/{id}','Dmkt\ActivityController@listActivitiesRM');
Route::get('listar-actividades-sup/{id}','Dmkt\ActivityController@listActivitiesSup');
Route::get('test2','Dmkt\SolicitudeController@test');


/** Supervisor */
Route::get('show_sup','Dmkt\SolicitudeController@show_sup');
Route::get('ver-solicitud-sup/{id}','Dmkt\SolicitudeController@viewSolicitudeSup');
Route::get('listar-solicitudes-sup/{id}','Dmkt\SolicitudeController@listSolicitudeSup');
Route::post('rechazar-solicitud','Dmkt\SolicitudeController@denySolicitude');
Route::post('aceptar-solicitud','Dmkt\SolicitudeController@aceptedSolicitude');

// ======================================================================

/**   Gastos */

Route::get('registrar-gasto','Expense\ExpenseController@show');
Route::post('consultarRuc','Expense\RucController@show');
// Route::get('test','Expense\RucController@show');
Route::get('ruc',function(){
    return View::make('Expense\ruc');
});
