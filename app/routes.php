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
use Illuminate\Database\Eloquent\ModelNotFoundException;

/** Login */
// route to show the login form
Route::get('login', array('uses' => 'Dmkt\LoginController@showLogin'));

// route to process the form
Route::post('login', array('uses' => 'Dmkt\LoginController@doLogin'));
Route::get('logout',array('uses' => 'Dmkt\LoginController@doLogout'));

/** Descargos  */
Route::get('/', function()
{
	return View::make('hello');
});

/** Auth */

Route::group(array('before' => 'rm'), function()
{
    /** Rep. Medico */


        Route::get('show_rm','Dmkt\SolicitudeController@show_rm');
        Route::get('nueva-solicitud','Dmkt\SolicitudeController@newSolicitude');

        Route::get('ver-solicitud/{token}','Dmkt\SolicitudeController@viewSolicitude');
        Route::get('editar-solicitud/{id}','Dmkt\SolicitudeController@editSolicitude');
        Route::post('editar-solicitud','Dmkt\SolicitudeController@formEditSolicitude');
        Route::get('getclients','Dmkt\SolicitudeController@getClients');
        Route::post('registrar-solicitud','Dmkt\SolicitudeController@registerSolicitude');
        Route::get('listar-solicitudes/{id}','Dmkt\SolicitudeController@listSolicitude');
        Route::get('getsubtypeactivities/{id}','Dmkt\SolicitudeController@subtypeactivity');
        Route::post('buscar-solicitudes','Dmkt\SolicitudeController@searchSolicituds');
        Route::post('cancelar-solicitud-rm','Dmkt\SolicitudeController@cancelSolicitude');


});

Route::group(array('before' => 'sup'), function()
{
    /** Supervisor */

    Route::get('show_sup','Dmkt\SolicitudeController@show_sup');
    Route::get('ver-solicitud-sup/{id}','Dmkt\SolicitudeController@viewSolicitudeSup');
    Route::get('listar-solicitudes-sup/{id}','Dmkt\SolicitudeController@listSolicitudeSup');
    Route::post('rechazar-solicitud','Dmkt\SolicitudeController@denySolicitude');
    Route::post('aceptar-solicitud','Dmkt\SolicitudeController@acceptedSolicitude');
    Route::post('buscar-solicitudes-sup','Dmkt\SolicitudeController@searchSolicitudsSup');
    Route::get('derivar-solicitud/{token}','Dmkt\SolicitudeController@derivedSolicitude');

});

/*------------------ Test --------------**/
Route::get('prueba','Dmkt\SolicitudeController@test');

/*
|-------------------------------------------------------------------------------------------- |
	                            | Gerente de Producto |
|-------------------------------------------------------------------------------------------- |
*/
Route::get('show_gerprod','Dmkt\SolicitudeController@show_gerprod');
Route::get('listar-solicitudes-gerprod/{id}','Dmkt\SolicitudeController@listSolicitudeGerProd');
Route::get('ver-solicitud-gerprod/{id}','Dmkt\SolicitudeController@viewSolicitudeGerProd');
Route::get('aprobar-solicitud/{token}','Dmkt\SolicitudeController@approvedSolicitude');


/*
|-------------------------------------------------------------------------------------------- |
	                            | Gerente Comercial |
|-------------------------------------------------------------------------------------------- |
*/
Route::get('show_gercom','Dmkt\SolicitudeController@show_gercom');
Route::get('listar-solicitudes-gercom/{id}','Dmkt\SolicitudeController@listSolicitudeGerCom');
Route::get('ver-solicitud-gercom/{id}','Dmkt\SolicitudeController@viewSolicitudeGerCom');
Route::get('aprobar-solicitud/{token}','Dmkt\SolicitudeController@approvedSolicitude');
// ======================================================================

 App::error(function(ModelNotFoundException $e)
 {

     return View::make('notfound');
 });


/**   Gastos */

// Expense
Route::post('registrar-gasto','Expense\ExpenseController@show');
Route::post('register-expense','Expense\ExpenseController@registerExpense');
Route::post('delete-expense','Expense\ExpenseController@deleteExpense');
Route::post('update-expense','Expense\ExpenseController@updateExpense');
Route::get('edit-expense','Expense\ExpenseController@editExpense');
Route::get('end-expense/{token}','Expense\ExpenseController@finishExpense');
Route::get('ver-gasto/{token}','Expense\ExpenseController@viewExpense');

// Ruc
Route::post('consultarRuc','Expense\RucController@show');
Route::get('ruc',function(){
    return View::make('Expense\ruc');
});

//test
Route::get('hola','Expense\ExpenseController@test');

//PDF
Route::get('a', function()
{
    $html = '<html><body>'
    		.'<header><img src="img/bkgr_logo.png"></header>'
            . '<p>Put your html here, or generate it with your favourite '
            . 'templating system.</p>'
            . '</body></html>';
    return PDF::load($html, 'A4', 'portrait')->show();
});