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
    Route::get('ver-solicitud-rm/{token}', 'Dmkt\SolicitudeController@viewSolicitude');
    //Route::get('getfondos/{id}', 'Dmkt\SolicitudeController@Fondo');
    //Route::get('show_rm', 'Dmkt\SolicitudeController@show_user');
    
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
Route::group(array('before' => 'sup'), function () {
    
    //Route::get('nueva-solicitud-sup', 'Dmkt\SolicitudeController@newSolicitude');
    Route::get('ver-solicitud-sup/{id}', 'Dmkt\SolicitudeController@viewSolicitudeSup');
    Route::get('aceptar-solicitud', 'Dmkt\SolicitudeController@redirectAcceptedSolicitude');
    Route::get('derivar-solicitud/{token}', 'Dmkt\SolicitudeController@derivedSolicitude');
    Route::get('desbloquear-solicitud-sup/{token}', 'Dmkt\SolicitudeController@disBlockSolicitudeSup');
    //Route::get('listar-solicitudes-sup/{id}', 'Dmkt\SolicitudeController@listSolicitudeSup');
    //Route::get('show_sup', 'Dmkt\SolicitudeController@show_user');
    //Route::post('buscar-solicitudes-sup', 'Dmkt\SolicitudeController@searchSolicitudsSup');
    //Route::post('cancelar-solicitud-sup', 'Dmkt\SolicitudeController@cancelSolicitudeSup');
    
});

/**
 * |-------------------------------------------------------------------------------------------- |
 * | Gerente de Producto |
 * |-------------------------------------------------------------------------------------------- |
 */
Route::group(array('before' => 'gerprod'), function () {
    Route::get('ver-solicitud-gerprod/{id}', 'Dmkt\SolicitudeController@viewSolicitudeGerProd');
    Route::get('cancelar-solicitud-gerprod/{token}', 'Dmkt\SolicitudeController@disBlockSolicitudeGerProd');
    //Route::get('aprobar-solicitud/{token}', 'Dmkt\SolicitudeController@approvedSolicitude');
    //Route::get('aceptar-solicitud-gerprod', 'Dmkt\SolicitudeController@redirectAcceptedSolicitudeGerProd');
    //Route::get('show_gerprod', 'Dmkt\SolicitudeController@show_user');
    //Route::get('listar-solicitudes-gerprod/{id}', 'Dmkt\SolicitudeController@listSolicitudeGerProd');
    //Route::post('aceptar-solicitud-gerprod', 'Dmkt\SolicitudeController@acceptedSolicitudeGerProd');
    //Route::post('buscar-solicitudes-gerprod', 'Dmkt\SolicitudeController@searchSolicitudsGerProd');
    //Route::post('rechazar-solicitud-gerprod', 'Dmkt\SolicitudeController@denySolicitudeGerProd');
    //Route::post('registrar-solicitud-gerprod', 'Dmkt\SolicitudeController@registerSolicitude');
});
/**
 * |-------------------------------------------------------------------------------------------- |
 * | Gerente Comercial |
 * |-------------------------------------------------------------------------------------------- |
 */
Route::group(array('before' => 'gercom'), function () {
    Route::get('ver-solicitud-gercom/{id}', 'Dmkt\SolicitudeController@viewSolicitudeGerCom');
    Route::post('aprobar-solicitud', 'Dmkt\SolicitudeController@approvedSolicitude');
    //Route::get('aprobar-solicitud', 'Dmkt\SolicitudeController@redirectApprovedSolicitude');
    Route::get('cancelar-solicitud-gercom/{token}', 'Dmkt\SolicitudeController@disBlockSolicitudeGerCom');
    Route::post('gercom-mass-approv','Dmkt\SolicitudeController@massApprovedSolicitudes');
    //Route::get('show_gercom', 'Dmkt\SolicitudeController@show_user');
    //Route::get('listar-solicitudes-gercom/{id}', 'Dmkt\SolicitudeController@listSolicitudeGerCom');
    //Route::post('buscar-solicitudes-gercom', 'Dmkt\SolicitudeController@searchSolicitudsGerCom');
    //Route::post('gercom-asignar-responsable','Dmkt\SolicitudeController@gercomAsignarResponsable');
    //Route::post('rechazar-solicitud-gercom', 'Dmkt\SolicitudeController@denySolicitudeGerCom');
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
    //Route::get('listar-solicitudes-cont/{id}', 'Dmkt\SolicitudeController@listSolicitudeCont');
    //Route::post('buscar-solicitudes-cont', 'Dmkt\SolicitudeController@searchSolicitudeCont');
    //Route::get('show_cont', 'Dmkt\SolicitudeController@show_cont');

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

Route::group(array('before' => 'tes'), function(){
    Route::get('ver-solicitud-tes/{token}', 'Deposit\DepositController@viewSolicitudeTes');
    Route::get('ver-fondo-tes/{token}', 'Deposit\DepositController@viewFondoTes');
    Route::post('deposit-solicitude', 'Deposit\DepositController@depositSolicitudeTes');
    Route::post('deposit-fondo','Deposit\DepositController@depositFondoTes');
    Route::get('list-fondos-tesoreria/{date}','Dmkt\FondoController@getFondosTesoreria');
    //Route::get('show_tes', 'Deposit\DepositController@show_tes');
    //Route::get('listar-solicitudes-tes/{id}', 'Deposit\DepositController@listSolicitudeTes');
    //Route::get('list-fondos-tesoreria','Deposit\DepositController@getFondos');
    //Route::post('buscar-solicitudes-tes', 'Deposit\DepositController@searchSolicitudeTes');
});

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
    //Route::get('representatives','Dmkt\FondoController@getRepresentatives');
    //Route::get('getctabanc/{dni}','Dmkt\FondoController@getCtaBanc');
    Route::get('exportfondos/{date}','Dmkt\FondoController@exportExcelFondos');
    Route::get('endfondos/{date}','Dmkt\FondoController@endfondos');
    Route::get('ver-solicitud-ager/{token}','Dmkt\SolicitudeController@viewSolicitudeAGer');
    Route::post('search-client', 'TestController@repSource');
    Route::post('search-rep', 'Source\Seeker@repSource');
    Route::post('info-rep', 'Source\Seeker@repInfo');
    //Route::get('listar-solicitudes-ager','Dmkt\SolicitudeController@listSolicitudeAGer');
});

Route::group(array('before' => 'rm_cont_tes_ager'), function ()
{
    Route::get('show-fondo/{token}','Expense\ExpenseController@showFondo');
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
});

Route::group(array('before' => 'rm_ager'), function ()
{
    Route::get('registrar-gasto/{token}', 'Expense\ExpenseController@show');
    Route::get('end-expense/{token}', 'Expense\ExpenseController@finishExpense');
    Route::get('ver-gasto/{token}', 'Expense\ExpenseController@viewExpense');
    Route::get('list-fondos/{date}','Dmkt\FondoController@getFondos');

    // Expense
    Route::get('edit-expense', 'Expense\ExpenseController@editExpense');
    Route::post('delete-expense', 'Expense\ExpenseController@deleteExpense');
    Route::post('update-expense', 'Expense\ExpenseController@updateExpense');
});
Route::group(array('before' => 'rm_sup'), function ()
{    
    Route::get('nueva-solicitud-rm', 'Dmkt\SolicitudeController@newSolicitude');
    Route::post('cancelar-solicitud-rm', 'Dmkt\SolicitudeController@cancelSolicitude');
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
});

App::missing(function ($exception) 
{
   // return Redirect::to('show_rm');
});
// App::error(function (ModelNotFoundException $e) {
//     return View::make('notfound');
// });

// Test

/*Route::get('test_expense', function()
{

    return Fondo::all();
});*/