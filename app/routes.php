<?php

    /*
    |--------------------------------------------------------------------------
    | TESTING
    |--------------------------------------------------------------------------
    */
    Route::get('test-carbon' , 'TestController@testCarbon' );
    Route::get('testalert' , 'TestController@testalert');
    Route::get('dt' , 'TestController@dt');
    Route::get('test', array('uses' => 'Dmkt\LoginController@test'));
    Route::get('set_status', array('uses' => 'BaseController@setStatus'));
    Route::get('sendmail', array('uses' => 'BaseController@postman'));
    Route::get('prueba', 'Dmkt\FondoController@test');

    Route::get( 'test-query' , 'TestController@testQuery' );
    Route::get( 'test-fondo-query' , 'TestController@getUserSubFondos' );
    Route::get( 'testcalert' , 'TestController@testcalert');
    Route::get( 'changePass/{id}' , 'TestController@changePass' );

    //--- TIME LINE --

    Route::get( 'timeline-modal/{id}' , 'Dmkt\SolicitudeController@getTimeLine');
    Route::post( 'agregar-familia-fondo', 'Dmkt\SolicitudeController@addFamilyFundSolicitud');
        
    /*
    |--------------------------------------------------------------------------
    | SYSTEM
    |--------------------------------------------------------------------------
    */

    Route::group( array( 'before' => 'developer' , 'namespace' => 'Seat' , 'prefix' => 'migrate' ) , function()
    {
        Route::get( 'seats2' , 'MigrateSeatController@migrateSeats');
    });

    Route::get( "logs", [ 
        "before" => "developer",
        "uses" => "\Rap2hpoutre\LaravelLogViewer\LogViewerController@index"
    ]);

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
    | GERENTE COMERCIAL
    |--------------------------------------------------------------------------
    */

    Route::group( array( 'before' => 'gercom' ) , function() 
    {
        Route::get('maintenance/FondosSupervisor', 'Maintenance\TableController@getSupFunds');
        Route::get('maintenance/FondosGerProd', 'Maintenance\TableController@getGerProdFunds');
        Route::get('maintenance/FondosInstitution', 'Maintenance\TableController@getInstitutionFunds');
        Route::get( 'fondoHistorial' , 'Fondo\FondoMkt@getFondoHistorial' );
        Route::post( 'fondo-subcategoria-history' , 'Fondo\FondoMkt@getFondoSubCategoryHistory' );
        Route::get( 'maintenance-export/{type}' , 'Maintenance\TableController@export' );
    });

    Route::group( array( 'before' => 'gercom_cont' ) , function()
    {
        Route::post( 'gercom-mass-approv' ,'Dmkt\SolicitudeController@massApprovedSolicitudes');
    });

    /*
    |--------------------------------------------------------------------------
    | CONTABILIDAD
    |--------------------------------------------------------------------------
    */
    
    Route::group( array( 'before' => 'cont' ), function () 
    {
        Route::post('revisar-solicitud', 'Dmkt\SolicitudeController@checkSolicitud');
        Route::get('revisar-asiento-solicitud/{token}', 'Dmkt\SolicitudeController@viewSeatSolicitude');
        Route::get('generar-asiento-solicitud/{token}', 'Dmkt\SolicitudeController@viewGenerateSeatSolicitude');
        Route::post('generate-seat-solicitude', 'Dmkt\SolicitudeController@generateSeatSolicitude');  
        Route::get('revisar-asiento-gasto/{token}', 'Dmkt\SolicitudeController@viewSeatExpense');
        Route::get('generar-asiento-gasto/{token}', 'Dmkt\SolicitudeController@viewGenerateSeatExpense');
        Route::post('guardar-asiento-gasto', 'Dmkt\SolicitudeController@saveSeatExpense');
        Route::post('get-account', 'Dmkt\SolicitudeController@getCuentaContHandler');
        Route::get('list-documents-type', 'Dmkt\FondoController@listDocuments');
        Route::post('list-documents', 'Movements\MoveController@searchDocs');
        
        Route::post('maintenance/cont-document-manage' , 'Expense\ExpenseController@manageDocument');
        Route::post('consultarRucCont', 'Expense\RucController@show');
        Route::get('edit-expense-cont', 'Expense\ExpenseController@editExpense');
        Route::post('get-document-detail' , 'Expense\ExpenseController@getDocument');
        Route::post('update-document' , 'Expense\ExpenseController@updateDocument');

        Route::get( 'export/solicitudToDeposit' , 'Export\ExportController@exportSolicitudToDeposit' );
        Route::post( 'end-expense-record' , 'Expense\ExpenseController@endExpenseRecord' );

        Route::post( 'modal-liquidation' , 'Deposit\DepositController@modalLiquidation' );
        Route::post( 'confirm-liquidation' , 'Deposit\DepositController@confirmLiquidation' );

    });

    /*
    |--------------------------------------------------------------------------
    | TESORERIA
    |--------------------------------------------------------------------------
    */

    Route::group( array( 'before' => 'tes' ), function()
    {
        Route::post( 'deposit-solicitude', 'Deposit\DepositController@depositSolicitudeTes');
        Route::post( 'modal-extorno' , 'Deposit\DepositController@modalExtorno' );
        Route::post( 'confirm-extorno' , 'Deposit\DepositController@confirmExtorno' );
    });

    /*
    |--------------------------------------------------------------------------
    | ASISTENTE GERENCIA
    |--------------------------------------------------------------------------
    */

    Route::group( array('before' => 'ager' ) , function()
    {
        Route::get('solicitude/institution', 'Dmkt\SolicitudeController@showSolicitudeInstitution');
        Route::post('registrar-fondo','Dmkt\FondoController@registerInstitutionalApplication');
        Route::get('exportfondos/{date}','Dmkt\FondoController@exportExcelFondos');
        Route::get('endfondos/{date}','Dmkt\FondoController@endfondos');
        Route::post('search-rep', 'Source\Seeker@repSource');
        Route::post('info-rep', 'Source\Seeker@repInfo');
        Route::get('list-fondos/{date}','Dmkt\FondoController@listInstitutionalSolicitud');
        Route::post('get-sol-inst' , 'Dmkt\FondoController@getSolInst');
        Route::post('search-institution', 'Source\Seeker@institutionSource');
    });

    /*
    |--------------------------------------------------------------------------
    | SUPERVISOR , GERENTE DE PRODUCTO - PROMOCION - COMERCIAL - GENERAL
    |--------------------------------------------------------------------------
    */

    Route::group(array('before' => 'sup_gerprod_gerprom_gercom_gerger'), function()
    {
        Route::post( 'search-users' , 'Source\Seeker@userSource');
        Route::post( 'confirm-temporal-user' , 'User\UserController@assignTemporalUser');
        Route::get( 'remove-temporal-user' , 'User\UserController@removeTemporalUser');
        Route::post('aceptar-solicitud', 'Dmkt\SolicitudeController@acceptedSolicitude');
    });

    /*
    |--------------------------------------------------------------------------
    | REPRESENTANTE , SUPERVISOR , GERENTE DE PRODUCTO - PROMOCION - COMERCIAL - GENERAL , CONTABILIDAD
    |--------------------------------------------------------------------------
    */    

    Route::group( array( 'before' => 'rm_sup_gerprod_gerprom_gercom_gerger_cont' ) , function()
    {
        Route::get('solicitude/statement', 'Movements\MoveController@getStatement');    
    });

    /*
    |--------------------------------------------------------------------------
    | REPRESENTANTE , CONTABILIDAD , TESORERIA
    |--------------------------------------------------------------------------
    */

    Route::group( array( 'before' => 'rm_cont_tes' ), function ()
    {
        Route::post( 'register-devolution-data' , 'Devolution\DevolutionController@registerDevolutionData' );
        Route::post( 'get-devolution-info' , 'Devolution\DevolutionController@getDevolutionInfo' );
        Route::post( 'get-inmediate-devolution-register-info' , 'Devolution\DevolutionController@getInmediateDevolutionRegisterInfo' );
        Route::post( 'get-inmediate-devolution-confirmation-info' , 'Devolution\DevolutionController@getInmediateDevolutionConfirmationInfo' );
        Route::post( 'get-payroll-info' , 'Devolution\DevolutionController@getPayrollInfo');
        Route::post( 'register-inmediate-devolution', 'Devolution\DevolutionController@registerInmediateDevolution' );
        Route::post( 'confirm-payroll-discount' , 'Devolution\DevolutionController@confirmPayrollDiscount' );
    });

    Route::group( array( 'before' => 'rm_sup' ), function ()
    {
        Route::post( 'end-expense', 'Expense\ExpenseController@finishExpense');
        Route::post( 'do-inmediate-devolution' , 'Devolution\DevolutionController@doInmediateDevolution' );
    });

    Route::group( array( 'before' => 'rm_sup_cont' ), function () 
    {
        Route::post( 'get-expenses' , 'Expense\ExpenseController@getExpenses');
        Route::post( 'edit-expense', 'Expense\ExpenseController@editExpense');
        Route::post( 'delete-expense', 'Expense\ExpenseController@deleteExpense');
        Route::post( 'register-expense', 'Expense\ExpenseController@registerExpense');
        Route::get( 'a/{token}', 'Expense\ExpenseController@reportExpense');
        Route::get( 'report-fondo/{token}','Expense\ExpenseController@reportExpenseFondo');
    });

    Route::group( array( 'before' => 'rm_sup_gerprod_ager' ), function ()
    {    
        Route::get( 'nueva-solicitud', 'Dmkt\SolicitudeController@newSolicitude');
        Route::get( 'get-investments-activities' , 'Dmkt\SolicitudeController@getInvestmentsActivities');
        Route::post( 'registrar-solicitud', 'Dmkt\SolicitudeController@registerSolicitud');
        Route::get( 'editar-solicitud/{id}', 'Dmkt\SolicitudeController@editSolicitud');
        //Route::post( 'search-client', 'Source\Seeker@clientSource');
        //Route::post( 'get-client-view' , 'Source\Seeker@getClientView');
        Route::post( 'filtro_cliente' , 'Dmkt\Client@getInvestmentActivity');
        Route::post( 'filtro-inversion' , 'Dmkt\Client@getActivities');  
        
    });

    Route::group( array( 'before' => 'rm_sup_gerprod_gerprom_gercom_gerger_ager_cont' ) , function()
    {
        Route::post('cancelar-solicitud', 'Dmkt\SolicitudeController@cancelSolicitud');
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
        Route::post( 'list-table' , 'Movements\MoveController@getTable');
        Route::post( 'detail-solicitud' , 'Movements\MoveController@getSolicitudDetail');
        Route::get('getleyenda', 'BaseController@getLeyenda');
        
        /*
        |--------------------------------------------------------------------------
        | Alert
        |--------------------------------------------------------------------------
        */
        //Route::get( 'alerts' , 'Alert\AlertController@show' );
        //Route::post( 'alerts' , 'Alert\AlertController@showAlerts' );
    
        /*
        |--------------------------------------------------------------------------
        | idkc: REPORT
        |--------------------------------------------------------------------------
        */
        // REPORT MAIN PAGE
        Route::get('reports', 'Report\ReportController@mainHandler');
        // GENERATE REPORT TABLE VIEW
        Route::get('reports/generate_html/{id_reporte}/{fromDate}/{toDate}', 'Report\ReportController@reportViewHandler'); 
        // CREATE EXCEL
        Route::post('reports/export/generate','Report\ReportController@reportExcelHandler');
        // LIST DATASET
        Route::get('reports/getQuerys', 'Report\ReportController@listDatasetHandler');
        // LIST COLUMNS OF DATASET
        Route::get('reports/getColumnsDataSet/{queryId}', 'Report\ReportController@listColumnsDatasetHandler');
        // SAVE NEW REPORT
        Route::post('reports/save', 'Report\ReportController@saveReportHandler');
        // LIST REPORTS OF USERS
        Route::get('reports/getUserReports', 'Report\ReportController@listReportsUserHandler');
        // SEND MAIL
        Route::post('mail_send','PostMan@sendEmailHandler');

        // DOWNLOAD REPORT EXCEL
        Route::get('reports/export/download/{userId}/{reportId}/{fromDate}/{toDate}', 'Report\ReportController@downloadReportExcelHandler');


    });

    App::missing(function ($exception) 
    {
    });
/*
|--------------------------------------------------------------------------
| MANTENIMIENTO
|--------------------------------------------------------------------------
*/
Route::get('maintenance/dailyseatrelation' , 'Maintenance\TableController@getMaintenanceTableDailySeatRelation');
Route::get('maintenance/fondos' , 'Maintenance\TableController@getMaintenanceTableDataFondos');
Route::get('maintenance/fondoaccount' , 'Maintenance\TableController@getViewFondoCuenta');
Route::get('maintenance/inversion' , 'Maintenance\TableController@getMaintenanceTableDataInversion');
Route::get('maintenance/activity' , 'Maintenance\TableController@getMaintenanceTableDataActivity');
Route::get('maintenance/investmentactivity' , 'Maintenance\TableController@getMaintenanceTableDataInvestmentActivity');
Route::get('maintenance/finddocument', 'Dmkt\SolicitudeController@findDocument');
Route::get('maintenance/documenttype', 'Dmkt\FondoController@listDocuments');
Route::get('maintenance/parameters', 'Maintenance\TableController@getMaintenanceTableParameter');
Route::get( 'maintenance/view/{type}' , 'Maintenance\TableController@getView' );


Route::post( 'get-cell-maintenance-info' , 'Maintenance\TableController@getMaintenanceCellData' );
Route::post( 'update-maintenance-info' , 'Maintenance\TableController@updateMaintenanceData' );
Route::post( 'save-maintenance-info' , 'Maintenance\TableController@saveMaintenanceData' );
Route::post( 'add-maintenance-info' , 'Maintenance\TableController@addMaintenanceData' );
Route::post( 'get-table-maintenance-info' , 'Maintenance\TableController@getMaintenanceTableData');
Route::post( 'maintenance-enable' , 'Maintenance\TableController@enableRecord');
Route::post( 'maintenance-disable' , 'Maintenance\TableController@disableRecord');

/*
|--------------------------------------------------------------------------
| EVENTOS
|--------------------------------------------------------------------------
*/
Route::post('createEvent', 'Dmkt\SolicitudeController@createEventHandler');
Route::get('eventos','Dmkt\SolicitudeController@album');
Route::post('eventos/list','Dmkt\SolicitudeController@getEventList');
Route::post('photos', 'Dmkt\SolicitudeController@photos');
Route::post('testUploadImgSave', 'Dmkt\SolicitudeController@viewTestUploadImgSave');

Route::post( 'search-client', 'Source\Seeker@clientSource');
Route::post( 'get-client-view' , 'Source\Seeker@getClientView');