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

/** Admin */
Route::group(array('before' => 'admin'), function () {
    Route::get('register', 'Admin\AdminController@register');
    Route::post('register-user', 'Admin\AdminController@formRegister');
    Route::get('editar/{id}', 'Admin\AdminController@edit');
    Route::post('edit-user', 'Admin\AdminController@formEditUser');
    Route::post('active-user', 'Admin\AdminController@activeUser');
    Route::post('look-user', 'Admin\AdminController@lookUser');
});

Route::get('search-user/{username}', function ($username) {

    $user = User::where('username', $username)->count();

    if ($user) {
        return 'SI';

    } else {
        return 'NO';
    }
});

/** Login */
// route to show the login form
Route::get('login', array('uses' => 'Dmkt\LoginController@showLogin'));

// route to process the form
Route::post('login', array('uses' => 'Dmkt\LoginController@doLogin'));
Route::get('logout', array('uses' => 'Dmkt\LoginController@doLogout'));

/** Descargos  */
Route::get('/', function () {
    return View::make('hello');
});
Route::get('recharge', function(){
    return View::make('recharge');
});
Route::get('getclients', 'Dmkt\SolicitudeController@getClients');

/** Auth */

Route::group(array('before' => 'rm'), function () {
    /** Rep. Medico */
    Route::get('show_rm', 'Dmkt\SolicitudeController@show_rm');
    Route::get('nueva-solicitud-rm', 'Dmkt\SolicitudeController@newSolicitude');
    Route::get('ver-solicitud-rm/{token}', 'Dmkt\SolicitudeController@viewSolicitude');
    Route::post('cancelar-solicitud-rm', 'Dmkt\SolicitudeController@cancelSolicitude');
    Route::get('listar-solicitudes-rm/{id}', 'Dmkt\SolicitudeController@listSolicitude');
    Route::get('getfondos/{id}', 'Dmkt\SolicitudeController@Fondo');
    Route::post('buscar-solicitudes-rm', 'Dmkt\SolicitudeController@searchSolicituds');

    // Expense
    Route::get('registrar-gasto/{token}', 'Expense\ExpenseController@show');
    Route::post('register-expense', 'Expense\ExpenseController@registerExpense');
    Route::post('delete-expense', 'Expense\ExpenseController@deleteExpense');
    Route::post('update-expense', 'Expense\ExpenseController@updateExpense');
    Route::get('edit-expense', 'Expense\ExpenseController@editExpense');
    Route::get('end-expense/{token}', 'Expense\ExpenseController@finishExpense');
    Route::get('ver-gasto/{token}', 'Expense\ExpenseController@viewExpense');

    // Ruc
    Route::post('consultarRuc', 'Expense\RucController@show');
    Route::get('ruc', function () {
        return View::make('Expense\ruc');
    });

});
Route::group(array('before' => 'auth'), function () {

    Route::post('registrar-solicitud', 'Dmkt\SolicitudeController@registerSolicitude');
    Route::get('editar-solicitud/{id}', 'Dmkt\SolicitudeController@editSolicitude');
    Route::post('editar-solicitud', 'Dmkt\SolicitudeController@formEditSolicitude');

});

Route::group(array('before' => 'sup'), function () {
    /** Supervisor */

    Route::get('show_sup', 'Dmkt\SolicitudeController@show_sup');
    Route::get('nueva-solicitud-sup', 'Dmkt\SolicitudeController@newSolicitude');
    Route::get('ver-solicitud-sup/{id}', 'Dmkt\SolicitudeController@viewSolicitudeSup');
    Route::get('listar-solicitudes-sup/{id}', 'Dmkt\SolicitudeController@listSolicitudeSup');
    Route::post('rechazar-solicitud', 'Dmkt\SolicitudeController@denySolicitude');
    Route::post('registrar-solicitud-gerprod', 'Dmkt\SolicitudeController@registerSolicitude');
    Route::post('aceptar-solicitud', 'Dmkt\SolicitudeController@acceptedSolicitude');
    Route::post('buscar-solicitudes-sup', 'Dmkt\SolicitudeController@searchSolicitudsSup');
    Route::get('derivar-solicitud/{token}', 'Dmkt\SolicitudeController@derivedSolicitude');
    Route::post('cancelar-solicitud-sup', 'Dmkt\SolicitudeController@cancelSolicitudeSup');
    Route::get('desbloquear-solicitud-sup/{token}', 'Dmkt\SolicitudeController@disBlockSolicitudeSup');
});

/*------------------ Test --------------**/
Route::get('prueba', 'Dmkt\SolicitudeController@test');


/**
 * |-------------------------------------------------------------------------------------------- |
 * | Fondo Institucional |
 * |-------------------------------------------------------------------------------------------- |
 */

Route::get('registrar-fondo','Dmkt\FondoController@getRegister');
Route::post('registrar-fondo','Dmkt\FondoController@postRegister');
Route::get('list-fondos','Dmkt\FondoController@getFondos');
Route::get('get-fondo/{id}','Dmkt\FondoController@getFondo');
Route::get('delete-fondo/{id}','Dmkt\FondoController@delFondo');
Route::post('update-fondo','Dmkt\FondoController@updateFondo');

/**
 * |-------------------------------------------------------------------------------------------- |
 * | Gerente de Producto |
 * |-------------------------------------------------------------------------------------------- |
 */
Route::group(array('before' => 'gerprod'), function () {

    Route::get('show_gerprod', 'Dmkt\SolicitudeController@show_gerprod');
    Route::get('listar-solicitudes-gerprod/{id}', 'Dmkt\SolicitudeController@listSolicitudeGerProd');
    Route::get('ver-solicitud-gerprod/{id}', 'Dmkt\SolicitudeController@viewSolicitudeGerProd');
    Route::get('aprobar-solicitud/{token}', 'Dmkt\SolicitudeController@approvedSolicitude');
    Route::post('aceptar-solicitud-gerprod', 'Dmkt\SolicitudeController@acceptedSolicitudeGerProd');
    Route::post('rechazar-solicitud-gerprod', 'Dmkt\SolicitudeController@denySolicitudeGerProd');
    Route::post('buscar-solicitudes-gerprod', 'Dmkt\SolicitudeController@searchSolicitudsGerProd');
    Route::get('cancelar-solicitud-gerprod/{token}', 'Dmkt\SolicitudeController@disBlockSolicitudeGerProd');

});
/**
 * |-------------------------------------------------------------------------------------------- |
 * | Gerente Comercial |
 * |-------------------------------------------------------------------------------------------- |
 */
Route::group(array('before' => 'gercom'), function () {

    Route::get('show_gercom', 'Dmkt\SolicitudeController@show_gercom');
    Route::get('listar-solicitudes-gercom/{id}', 'Dmkt\SolicitudeController@listSolicitudeGerCom');
    Route::get('ver-solicitud-gercom/{id}', 'Dmkt\SolicitudeController@viewSolicitudeGerCom');
    Route::post('aprobar-solicitud', 'Dmkt\SolicitudeController@approvedSolicitude');
    Route::post('buscar-solicitudes-gercom', 'Dmkt\SolicitudeController@searchSolicitudsGerCom');
    Route::post('rechazar-solicitud-gercom', 'Dmkt\SolicitudeController@denySolicitudeGerCom');

});

/**
 * |-------------------------------------------------------------------------------------------- |
 * | Contabilidad |
 * |-------------------------------------------------------------------------------------------- |
 */

Route::group(array('before' => 'cont'), function () {

    Route::get('show_cont', 'Dmkt\SolicitudeController@show_cont');
    Route::get('ver-solicitud-cont/{id}', 'Dmkt\SolicitudeController@viewSolicitudeCont');
    Route::get('listar-solicitudes-cont/{id}', 'Dmkt\SolicitudeController@listSolicitudeCont');
    Route::post('buscar-solicitudes-cont', 'Dmkt\SolicitudeController@searchSolicitudeCont');
    Route::post('enable-deposit', 'Dmkt\SolicitudeController@enableDeposit');
    Route::get('generar-asiento-solicitud/{token}', 'Dmkt\SolicitudeController@viewSeatSolicitude');
    Route::post('generate-seat-solicitude', 'Dmkt\SolicitudeController@generateSeatSolicitude');
    Route::get('generar-asiento-gasto/{token}', 'Dmkt\SolicitudeController@viewSeatExpense');
    Route::post('generate-seat-expense', 'Dmkt\SolicitudeController@generateSeatExpense');
});
// App::error(function (ModelNotFoundException $e) {
//     return View::make('notfound');
// });

App::missing(function ($exception) {
    return Redirect::to('show_rm');
});

/**   Gastos */

/**
 * |-------------------------------------------------------------------------------------------- |
 * | Tesorería |
 * |-------------------------------------------------------------------------------------------- |
 */

Route::group(array('before' => 'tes'), function(){

    Route::get('show_tes', 'Deposit\DepositController@show_tes');
    Route::get('listar-solicitudes-tes/{id}', 'Deposit\DepositController@listSolicitudeTes');
    Route::get('ver-solicitud-tes/{id}', 'Deposit\DepositController@viewSolicitudeTes');
    Route::post('buscar-solicitudes-tes', 'Deposit\DepositController@searchSolicitudeTes');
    Route::post('depositar', 'Deposit\DepositController@depositSolicitudeTes');
    
});

/* ================================================================================= */


//test
Route::get('hola', 'Expense\ExpenseController@test');
Route::get('a/{token}', 'Expense\ExpenseController@reportExpense');

Route::get('report', 'ExpenseController@reportExpense');
