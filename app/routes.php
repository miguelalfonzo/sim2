<?php
/*

use Common/BrandExpense;
use Common/Fondo;

|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('test', array('uses' => 'Dmkt\LoginController@test'));

/** Login */
Route::get('/', array('uses' => 'Dmkt\LoginController@showLogin'));
Route::get('login', array('uses' => 'Dmkt\LoginController@showLogin'));
Route::post('login', array('uses' => 'Dmkt\LoginController@doLogin'));
Route::get('logout', array('uses' => 'Dmkt\LoginController@doLogout'));

/** Descargos  */
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
    Route::post('delete-expense', 'Expense\ExpenseController@deleteExpense');
    Route::post('update-expense', 'Expense\ExpenseController@updateExpense');
    Route::get('edit-expense', 'Expense\ExpenseController@editExpense');
    // Ruc
    Route::post('consultarRuc', 'Expense\RucController@show');
    Route::get('ruc', function () {
        return View::make('Expense\ruc');
    });
    // Fondo
    Route::get('list-fondos-rm','Dmkt\FondoController@listFondosRep');
    Route::get('registrar-gasto-fondo/{token}', 'Expense\ExpenseController@showRegisterFondo');
    Route::get('end-expense-fondo/{id}','Expense\ExpenseController@finishExpenseFondo');
    Route::get('ver-gasto-fondo/{token}','Expense\ExpenseController@viewExpenseFondo');
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
    Route::get('aceptar-solicitud', 'Dmkt\SolicitudeController@redirectAcceptedSolicitude');
    Route::post('buscar-solicitudes-sup', 'Dmkt\SolicitudeController@searchSolicitudsSup');
    Route::get('derivar-solicitud/{token}', 'Dmkt\SolicitudeController@derivedSolicitude');
    Route::post('cancelar-solicitud-sup', 'Dmkt\SolicitudeController@cancelSolicitudeSup');
    Route::get('desbloquear-solicitud-sup/{token}', 'Dmkt\SolicitudeController@disBlockSolicitudeSup');
});
/*------------------ Test --------------**/
Route::get('prueba', 'Dmkt\FondoController@test');

/**
 * |-------------------------------------------------------------------------------------------- |
 * | Fondo Institucional |
 * |-------------------------------------------------------------------------------------------- |
 */
Route::group(array ('before' => 'ager') ,function(){

    Route::get('registrar-fondo','Dmkt\FondoController@getRegister');
    Route::post('registrar-fondo','Dmkt\FondoController@postRegister');
    Route::get('get-fondo/{id}','Dmkt\FondoController@getFondo');
    Route::post('delete-fondo','Dmkt\FondoController@delFondo');
    Route::post('update-fondo','Dmkt\FondoController@updateFondo');
    Route::get('representatives','Dmkt\FondoController@getRepresentatives');
    Route::get('getctabanc/{dni}','Dmkt\FondoController@getCtaBanc');
    Route::get('exportfondos/{date}','Dmkt\FondoController@exportExcelFondos');
    Route::get('endfondos/{date}','Dmkt\FondoController@endfondos');
    Route::get('listar-solicitudes-ager','Dmkt\SolicitudeController@listSolicitudeAGer');
    Route::get('ver-solicitud-ager/{token}','Dmkt\SolicitudeController@viewSolicitudeAGer');
});

Route::get('list-fondos/{date}','Dmkt\FondoController@getFondos');
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
    Route::get('aceptar-solicitud-gerprod', 'Dmkt\SolicitudeController@redirectAcceptedSolicitudeGerProd');
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
    Route::get('aprobar-solicitud', 'Dmkt\SolicitudeController@redirectApprovedSolicitude');
    Route::post('buscar-solicitudes-gercom', 'Dmkt\SolicitudeController@searchSolicitudsGerCom');
    Route::post('rechazar-solicitud-gercom', 'Dmkt\SolicitudeController@denySolicitudeGerCom');
    Route::get('cancelar-solicitud-gercom/{token}', 'Dmkt\SolicitudeController@disBlockSolicitudeGerCom');
    Route::post('gercom-asignar-responsable','Dmkt\SolicitudeController@gercomAsignarResponsable');

});

/**
 * |-------------------------------------------------------------------------------------------- |
 * | Contabilidad |
 * |-------------------------------------------------------  ------------------------------------- |
 */

Route::group(array('before' => 'cont'), function () {
    Route::get('show_cont', 'Dmkt\SolicitudeController@show_cont');
    Route::get('ver-solicitud-cont/{id}', 'Dmkt\SolicitudeController@viewSolicitudeCont');
    Route::get('listar-solicitudes-cont/{id}', 'Dmkt\SolicitudeController@listSolicitudeCont');
    Route::post('buscar-solicitudes-cont', 'Dmkt\SolicitudeController@searchSolicitudeCont');
    Route::post('enable-deposit', 'Dmkt\SolicitudeController@enableDeposit');
    Route::get('revisar-asiento-solicitud/{token}', 'Dmkt\SolicitudeController@viewSeatSolicitude');
    Route::get('generar-asiento-solicitud/{token}', 'Dmkt\SolicitudeController@viewGenerateSeatSolicitude');
    Route::post('generate-seat-solicitude', 'Dmkt\SolicitudeController@generateSeatSolicitude');  
    Route::get('revisar-asiento-gasto/{token}', 'Dmkt\SolicitudeController@viewSeatExpense');
    Route::get('generar-asiento-gasto/{token}', 'Dmkt\SolicitudeController@viewGenerateSeatExpense');
    Route::post('guardar-asiento-gasto', 'Dmkt\SolicitudeController@saveSeatExpense');
    Route::post('generate-seat-expense', 'Dmkt\SolicitudeController@generateSeatExpense');
    //RM
    Route::get('revisar-gasto/{token}', 'Expense\ExpenseController@showCont');
    Route::post('consultarRucCont', 'Expense\RucController@show');
    Route::get('edit-expense-cont', 'Expense\ExpenseController@editExpense');
    Route::post('update-expense-cont', 'Expense\ExpenseController@updateExpense');
    //Fondos
    Route::get('list-fondos-contabilidad/{date}/{estado}','Dmkt\FondoController@getFondosContabilidad');
    Route::get('generar-asiento-fondo/{token}', 'Dmkt\FondoController@viewGenerateSeatFondo');
    Route::post('generate-seat-fondo', 'Dmkt\FondoController@generateSeatFondo');
});
// App::error(function (ModelNotFoundException $e) {
//     return View::make('notfound');
// });
App::missing(function ($exception) {
   // return Redirect::to('show_rm');
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
    Route::post('deposit-solicitude', 'Deposit\DepositController@depositSolicitudeTes');
    //Route::get('list-fondos-tesoreria','Deposit\DepositController@getFondos');
    Route::post('deposit-fondo','Deposit\DepositController@depositFondoTes');
    Route::get('list-fondos-tesoreria/{date}','Dmkt\FondoController@getFondosTesoreria');
});
//Test
Route::get('hola', 'Expense\ExpenseController@test');
Route::get('a/{token}', 'Expense\ExpenseController@reportExpense');
Route::get('report-fondo/{token}','Expense\ExpenseController@reportExpenseFondo');
Route::get('report', 'ExpenseController@reportExpense');

Route::group(array('before' => 'rm_cont_ager'), function ()
{
    Route::post('register-expense', 'Expense\ExpenseController@registerExpense');
});
Route::group(array('before' => 'sup_gerprod'), function ()
{
    Route::post('asignar-solicitud-responsable', 'Dmkt\SolicitudeController@asignarResponsableSolicitud');
});
Route::group(array('before' => 'rm_ager'), function ()
{
    Route::get('registrar-gasto/{token}', 'Expense\ExpenseController@show');
    Route::get('end-expense/{token}', 'Expense\ExpenseController@finishExpense');
    Route::get('ver-gasto/{token}', 'Expense\ExpenseController@viewExpense');  
});

// Test

/*Route::get('test_expense', function()
{

    return Fondo::all();
});*/
