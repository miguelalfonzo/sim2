<?php

/* TEST */
Route::get('test', array('uses' => 'Dmkt\LoginController@test'));
Route::get('set_status', array('uses' => 'BaseController@setStatus'));
Route::get('sendmail', array('uses' => 'BaseController@postman'));
Route::get('prueba', 'Dmkt\FondoController@test');


Route::get('clientes-data','TestController@clientsTables');
Route::get('hola', 'Expense\ExpenseController@test');
Route::get('a/{token}', 'Expense\ExpenseController@reportExpense');
Route::get('report-fondo/{token}','Expense\ExpenseController@reportExpenseFondo');
Route::get('report', 'ExpenseController@reportExpense');
Route::get('tmp','TestController@tm');
Route::get('history','TestController@withHistory');

/** Login */
Route::get('/', array('uses' => 'Dmkt\LoginController@showLogin'));
Route::get('login', array('uses' => 'Dmkt\LoginController@showLogin'));
Route::post('login', array('uses' => 'Dmkt\LoginController@doLogin'));
Route::get('logout', array('uses' => 'Dmkt\LoginController@doLogout'));

/** Descargos  */
Route::get('recharge', function(){
    return View::make('recharge');
});

/**
 * |-------------------------------------------------------------------------------------------- |
 * | Representante Medico |
 * |-------------------------------------------------------------------------------------------- |
 */
Route::group(array('before' => 'rm'), function () 
{    
    
    // Fondo
    Route::get('list-fondos-rm','Dmkt\FondoController@listFondosRep');
    Route::get('registrar-gasto-fondo/{token}', 'Expense\ExpenseController@showRegisterFondo');
    Route::get('end-expense-fondo/{id}','Expense\ExpenseController@finishExpenseFondo');
    
    // Ruc
    Route::post('consultarRuc', 'Expense\RucController@show');
    Route::get('ruc', function () {
        return View::make('Expense\ruc');
    });
});

/**
 * |-------------------------------------------------------------------------------------------- |
 * | SUPERVISOR |
 * |-------------------------------------------------------------------------------------------- |
 */
Route::group(array('before' => 'sup'), function () 
{
    Route::get('ver-solicitud-sup/{id}', 'Dmkt\SolicitudeController@viewSolicitudeSup');
    Route::get('aceptar-solicitud', 'Dmkt\SolicitudeController@redirectAcceptedSolicitude');
    Route::get('derivar-solicitud/{token}', 'Dmkt\SolicitudeController@derivedSolicitude');
});

/**
 * |-------------------------------------------------------------------------------------------- |
 * | Gerente de Producto |
 * |-------------------------------------------------------------------------------------------- |
 */
Route::group(array('before' => 'gerprod'), function () 
{
    Route::get('ver-solicitud-gerprod/{id}', 'Dmkt\SolicitudeController@viewSolicitudeGerProd');
});
/**
 * |-------------------------------------------------------------------------------------------- |
 * | Gerente Comercial |
 * |-------------------------------------------------------------------------------------------- |
 */
Route::group(array('before' => 'gercom'), function () 
{
    Route::get('ver-solicitud-gercom/{id}', 'Dmkt\SolicitudeController@viewSolicitudeGerCom');
    Route::post('aprobar-solicitud', 'Dmkt\SolicitudeController@approvedSolicitude');
    Route::post('gercom-mass-approv','Dmkt\SolicitudeController@massApprovedSolicitudes');
});

/**
 * |-------------------------------------------------------------------------------------------- |
 * | Contabilidad |
 * |-------------------------------------------------------  ------------------------------------- |
 */

Route::group(array('before' => 'cont'), function () {
    Route::get('ver-solicitud-cont/{id}', 'Dmkt\SolicitudeController@viewSolicitudeCont');
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
    Route::get('generar-asiento-fondo-gasto/{token}', 'Dmkt\SolicitudeController@viewGenerateSeatExpense');
    Route::post('generate-seat-fondo', 'Dmkt\FondoController@generateSeatFondo');
    Route::post('get-account', 'Dmkt\SolicitudeController@getCuentaContHandler');
    Route::get('list-documents', 'Dmkt\FondoController@listDocuments');
    Route::post('cont-document-manage' , 'Expense\ExpenseController@manageDocument');
});

/**
 * |-------------------------------------------------------------------------------------------- |
 * | Tesorería |
 * |-------------------------------------------------------------------------------------------- |
 */

Route::group(array('before' => 'tes'), function()
{
    Route::get('ver-solicitud-tes/{token}', 'Deposit\DepositController@viewSolicitudeTes');
    Route::get('ver-fondo-tes/{token}', 'Deposit\DepositController@viewFondoTes');
    Route::post('deposit-solicitude', 'Deposit\DepositController@depositSolicitudeTes');
    Route::post('deposit-fondo','Deposit\DepositController@depositFondoTes');
    Route::get('list-fondos-tesoreria/{date}','Dmkt\FondoController@getFondosTesoreria');
});

/**
 * |-------------------------------------------------------------------------------------------- |
 * | Fondo Institucional |
 * |-------------------------------------------------------------------------------------------- |
 */

Route::group( array('before' => 'ager') , function()
{
    //Route::get('registrar-fondo','Dmkt\FondoController@getRegister');
    Route::post('registrar-fondo','Dmkt\FondoController@postRegister');
    //Route::get('get-fondo/{id}','Dmkt\FondoController@getFondo');
    Route::post('update-fondo','Dmkt\FondoController@updateFondo');
    Route::get('exportfondos/{date}','Dmkt\FondoController@exportExcelFondos');
    Route::get('endfondos/{date}','Dmkt\FondoController@endfondos');
    //Route::get('ver-solicitud-ager/{token}','Dmkt\SolicitudeController@viewSolicitudeAGer');
    //Route::post('search-client', 'TestController@repSource');
    Route::post('search-rep', 'Source\Seeker@repSource');
    Route::post('info-rep', 'Source\Seeker@repInfo');
    Route::get('list-fondos/{date}','Dmkt\FondoController@getFondos');
    Route::post('get-sol-inst' , 'Dmkt\FondoController@getSolInst');
});


Route::group( array( 'before' => 'rm_sup_ager' ), function ()
{
    Route::post('cancelar-solicitud', 'Dmkt\SolicitudeController@cancelSolicitude');
});

Route::group(array('before' => 'rm_cont_ager'), function ()
{
    Route::post('register-expense', 'Expense\ExpenseController@registerExpense');
});
Route::group(array('before' => 'sup_gerprod'), function ()
{
    Route::post('asignar-solicitud-responsable', 'Dmkt\SolicitudeController@asignarResponsableSolicitud');
    Route::post('aceptar-solicitud', 'Dmkt\SolicitudeController@acceptedSolicitude');
    Route::post('buscar-responsable' , 'Dmkt\SolicitudeController@getResponsables');

    
});

Route::group(array('before' => 'sup_gerprod_gercom'), function ()
{
    Route::post('rechazar-solicitud', 'Dmkt\SolicitudeController@denySolicitude');
    Route::get('desbloquear-solicitud/{token}', 'Dmkt\SolicitudeController@disBlockSolicitude');
});

Route::group(array('before' => 'rm_ager'), function ()
{
    Route::get('registrar-gasto/{token}', 'Expense\ExpenseController@show');
    Route::get('end-expense/{token}', 'Expense\ExpenseController@finishExpense');
    Route::get('ver-gasto/{token}', 'Expense\ExpenseController@viewExpense');
    
    // Expense
    Route::get('edit-expense', 'Expense\ExpenseController@editExpense');
    Route::post('delete-expense', 'Expense\ExpenseController@deleteExpense');
    Route::post('update-expense', 'Expense\ExpenseController@updateExpense');
});
Route::group(array('before' => 'rm_sup'), function ()
{    
    Route::get('nueva-solicitud-rm', 'Dmkt\SolicitudeController@newSolicitude');
    Route::post('registrar-solicitud', 'Dmkt\SolicitudeController@registerSolicitude');
    Route::get('editar-solicitud/{id}', 'Dmkt\SolicitudeController@editSolicitude');
    Route::post('editar-solicitud', 'Dmkt\SolicitudeController@formEditSolicitude');
    Route::post('search-client', 'Source\Seeker@clientSource');
});

Route::group(array('before' => 'sys_user'), function ()
{
    Route::post('buscar-solicitudes', 'Dmkt\SolicitudeController@searchDmkt');
    Route::get('listar-solicitudes/{estado}', 'Dmkt\SolicitudeController@listSolicitude');
    Route::get('getclients', 'Dmkt\SolicitudeController@getClients');
    Route::post('list-account-state', 'Movements\MoveController@searchMove');
    Route::get('show_user', 'Dmkt\SolicitudeController@showUser');
    Route::get('ver-solicitud/{token}', 'Dmkt\SolicitudeController@viewSolicitude');
    Route::get('show-fondo/{token}','Expense\ExpenseController@showFondo');
    
});

App::missing(function ($exception) 
{

});
