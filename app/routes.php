<?php

/*
|--------------------------------------------------------------------------
| TESTING
|--------------------------------------------------------------------------
*/
Route::get('test', array('uses' => 'Dmkt\LoginController@test'));
Route::get('set_status', array('uses' => 'BaseController@setStatus'));
Route::get('sendmail', array('uses' => 'BaseController@postman'));
Route::get('prueba', 'Dmkt\FondoController@test');
Route::get('testUploadImg', 'BaseController@viewTestUploadImg');
Route::post('testUploadImgSave', 'BaseController@viewTestUploadImgSave');

Route::get('clientes-data','TestController@clientsTables');
Route::get('hola', 'Expense\ExpenseController@test');
Route::get('a/{token}', 'Expense\ExpenseController@reportExpense');
Route::get('report-fondo/{token}','Expense\ExpenseController@reportExpenseFondo');
Route::get('report', 'ExpenseController@reportExpense');
Route::get('tmp','TestController@tm');
Route::get('history','TestController@withHistory');

Route::post( 'get-cell-maintenance-info' , 'Maintenance\TableController@getMaintenanceCellData' );
Route::post( 'update-maintenance-info' , 'Maintenance\TableController@updateMaintenanceData' );
Route::post( 'save-maintenance-info' , 'Maintenance\TableController@saveMaintenanceData' );
Route::post( 'add-maintenance-info' , 'Maintenance\TableController@addMaintenanceData' );
Route::post( 'get-table-maintenance-info' , 'Maintenance\TableController@getMaintenanceTableData');

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/', array('uses' => 'Dmkt\LoginController@showLogin'));
Route::get('login', array('uses' => 'Dmkt\LoginController@showLogin'));
Route::post('login', array('uses' => 'Dmkt\LoginController@doLogin'));
Route::get('logout', array('uses' => 'Dmkt\LoginController@doLogout'));

/*
|--------------------------------------------------------------------------
| DESCARGOS
|--------------------------------------------------------------------------
*/

Route::get('recharge', function(){
    return View::make('recharge');
});

/*
|--------------------------------------------------------------------------
| SUPERVISOR
|--------------------------------------------------------------------------
*/

Route::group(array('before' => 'sup'), function () 
{
    Route::post('buscar-gerprod', 'Dmkt\SolicitudeController@findGerProd');
    Route::post('derivar-solicitud', 'Dmkt\SolicitudeController@deriveSolRep');
});

/*
|--------------------------------------------------------------------------
| GERENTE COMERCIAL
|--------------------------------------------------------------------------
*/

Route::group(array('before' => 'gercom'), function () 
{
    Route::post('aprobar-solicitud', 'Dmkt\SolicitudeController@approvedSolicitude');
    Route::post('gercom-mass-approv','Dmkt\SolicitudeController@massApprovedSolicitudes');
});

/*
|--------------------------------------------------------------------------
| CONTABILIDAD
|--------------------------------------------------------------------------
*/

Route::group(array('before' => 'cont'), function () 
{
    Route::post('enable-deposit', 'Dmkt\SolicitudeController@enableDeposit');
    Route::get('revisar-asiento-solicitud/{token}', 'Dmkt\SolicitudeController@viewSeatSolicitude');
    Route::get('generar-asiento-solicitud/{token}', 'Dmkt\SolicitudeController@viewGenerateSeatSolicitude');
    Route::post('generate-seat-solicitude', 'Dmkt\SolicitudeController@generateSeatSolicitude');  
    Route::get('revisar-asiento-gasto/{token}', 'Dmkt\SolicitudeController@viewSeatExpense');
    Route::get('generar-asiento-gasto/{token}', 'Dmkt\SolicitudeController@viewGenerateSeatExpense');
    Route::post('guardar-asiento-gasto', 'Dmkt\SolicitudeController@saveSeatExpense');
    Route::post('generate-seat-expense', 'Dmkt\SolicitudeController@generateSeatExpense');
    Route::post('get-account', 'Dmkt\SolicitudeController@getCuentaContHandler');
    Route::get('list-documents-type', 'Dmkt\FondoController@listDocuments');
    Route::post('list-documents', 'Movements\MoveController@searchDocs');
    
    Route::post('cont-document-manage' , 'Expense\ExpenseController@manageDocument');
    Route::post('consultarRucCont', 'Expense\RucController@show');
    Route::get('edit-expense-cont', 'Expense\ExpenseController@editExpense');
    Route::post('get-document-detail' , 'Expense\ExpenseController@getDocument');
    Route::post('update-document' , 'Expense\ExpenseController@updateDocument');

    //RM
    //Route::get('revisar-gasto/{token}', 'Expense\ExpenseController@showCont');
    //Route::post('update-expense-cont', 'Expense\ExpenseController@updateExpense');

    //Fondos
    //Route::get('list-fondos-contabilidad/{date}/{estado}','Dmkt\FondoController@getFondosContabilidad');
    //Route::get('generar-asiento-fondo/{token}', 'Dmkt\FondoController@viewGenerateSeatFondo');
    //Route::get('generar-asiento-fondo-gasto/{token}', 'Dmkt\SolicitudeController@viewGenerateSeatExpense');
    //Route::post('generate-seat-fondo', 'Dmkt\FondoController@generateSeatFondo');
});
/*
|--------------------------------------------------------------------------
| TESORERIA
|--------------------------------------------------------------------------
*/

Route::group(array('before' => 'tes'), function()
{
    Route::post( 'deposit-solicitude', 'Deposit\DepositController@depositSolicitudeTes');
    Route::get( 'list-fondos' , 'Dmkt\FondoController@getFondos');
});
/*
|--------------------------------------------------------------------------
| ASISTENTE GERENCIA
|--------------------------------------------------------------------------
*/

Route::group( array('before' => 'ager') , function()
{
    Route::post('registrar-fondo','Dmkt\FondoController@postRegister');
    Route::post('update-fondo','Dmkt\FondoController@updateSolInst');
    Route::get('exportfondos/{date}','Dmkt\FondoController@exportExcelFondos');
    Route::get('endfondos/{date}','Dmkt\FondoController@endfondos');
    Route::post('search-rep', 'Source\Seeker@repSource');
    Route::post('info-rep', 'Source\Seeker@repInfo');
    Route::get('list-fondos/{date}','Dmkt\FondoController@listSolInst');
    Route::post('get-sol-inst' , 'Dmkt\FondoController@getSolInst');
});

Route::group( array( 'before' => 'rm_sup_ager' ), function ()
{
    Route::post('cancelar-solicitud', 'Dmkt\SolicitudeController@cancelSolicitude');
});

Route::group(array('before' => 'sup_gerprod'), function ()
{
    Route::post('asignar-solicitud-responsable', 'Dmkt\SolicitudeController@asignarResponsableSolicitud');
    Route::post('aceptar-solicitud', 'Dmkt\SolicitudeController@acceptedSolicitude');
    Route::post('buscar-responsable' , 'Dmkt\SolicitudeController@findResponsables');
});

Route::group(array('before' => 'sup_gerprod_gercom'), function ()
{
    Route::post('rechazar-solicitud', 'Dmkt\SolicitudeController@denySolicitude');
});

Route::group(array('before' => 'rm_cont_ager'), function () 
{
    Route::post('get-expenses' , 'Expense\ExpenseController@getExpenses');
    Route::post('edit-expense', 'Expense\ExpenseController@editExpense');
    Route::post('delete-expense', 'Expense\ExpenseController@deleteExpense');
    Route::post('register-expense', 'Expense\ExpenseController@registerExpense');
    Route::post('update-expense', 'Expense\ExpenseController@updateExpense');
    Route::get('a/{token}', 'Expense\ExpenseController@reportExpense');
    Route::get('report-fondo/{token}','Expense\ExpenseController@reportExpenseFondo');
});

Route::group(array('before' => 'rm_ager'), function ()
{
    //Route::get('registrar-gasto/{token}', 'Expense\ExpenseController@show');
    Route::post('end-expense', 'Expense\ExpenseController@finishExpense');
    Route::get('ver-gasto/{token}', 'Expense\ExpenseController@viewExpense');
    
    // Expense
    
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
