<?php 

const TIPO_ASIENTO_ANTICIPO = 'A';
const IMAGE_PATH = 'img/reembolso/';
const WIDTH = 800;
const HEIGHT = 600;
const data = 'Data';
const ok = 'Ok';
const warning = 'Warning';
const danger = 'Danger';
const error = 'Error';
const desc_error = 'Hubo un error al procesar la informacion';
const status = 'Status';
const description = 'Description';
const DB = 'Base de Datos';
const CUENTA_BAGO = 'B';
//const DOCUMENTO_NO_SUSTENTABLE_ID = 7;

const SOLES = 1 ;
const DOLARES = 2;

// ASIENTOS DE GASTOS
const ASIENTO_GASTO_IVA_BASE 					= 'N6';
const ASIENTO_GASTO_IVA_IGV 					= 'I6';
const ASIENTO_GASTO_COD_PROV_IGV				= '90000';
const ASIENTO_GASTO_COD_PROV				  	= '';
const ASIENTO_GASTO_COD_IGV			     		= '80';
const ASIENTO_GASTO_COD			  		 		= '';
const ASIENTO_GASTO_BASE 					  	= 'D';
const ASIENTO_GASTO_DEPOSITO				  	= 'C';
const ASIENTO_GASTO_TIPO						= 'G';
const ERROR_NOT_FOUND_MATCH_ACCOUNT_MKT_CNT   	= 'No se encontro cuenta relacionada';
const MESSAGE_NOT_FOUND_MATCH_ACCOUNT_MKT_CNT 	= 'Verificar relación entre las cuentas de Marketing y Contabilidad';
const ERROR_NOT_FOUND_MARCA					  	= 'No se encontro Marca';
const MESSAGE_NOT_FOUND_MARCA				  	= 'Verificar relación entre las Cuentas de Contabilidad y las Marcas';
const CUENTA_REPARO_COMPRAS						= '6411000';//debe
const CUENTA_REPARO_GOBIERNO					= '4011100';//haber
const CUENTA_RETENCION_DEBE						= 1681000;
const CUENTA_RETENCION_HABER				 	= 4011400;
const CUENTA_DETRACCION_HABER					= 4212300;
const CUENTA_RENTA_4TA_HABER					= 4017200;
const CUENTA_DETRACCION_REEMBOLSO				= 4699700;
const ERROR_INVALID_ACCOUNT_MKT					= 'Error de Cuenta';
const MSG_INVALID_ACCOUNT_MKT					= 'Verificar campo de cuenta';

//ESTADOS ADICIONALES
const ESTADO_DERIVADO = 'DERIVADO';
const ESTADO_RETENCION = 'RETENCION';
const ESTADO_DEPOSITADO = 'DEPOSITADO'; 
const ESTADO_ACEPTADO = 'POR APROBAR';

//ID USUARIOS
const USER_CONTABILIDAD = 43;
const USER_GERENTE_COMERCIAL = 17;
const USER_TESORERIA = 42;

// EMAIL DE PRUEBAS
const POSTMAN_USER_EMAIL	= 'manueltemple@gmail.com';
const POSTMAN_USER_NAME		= 'Manuel Temple';
const SOPORTE_EMAIL         = 'jortiz@esinergy.com';

//ESTADO RANGOS
const R_PENDIENTE = 1;
const R_APROBADO = 2;
const R_REVISADO = 3;
const R_GASTO = 4;
const R_FINALIZADO = 5;
const R_NO_AUTORIZADO = 6;
const R_TODOS = 10;

//CODIGO USUARIOS
const REP_MED   = 'R';
const SUP 	    = 'S';
const GER_PROD  = 'P';
const GER_PROM  = 'GP';
const GER_COM   = 'G';
const CONT 	    = 'C';
const TESORERIA = 'T';
const ASIS_GER  = 'AG';

//TABLAS EXTERNAS
const TB_DOCTOR = 'FICPE.PERSONAFIS';
const TB_INSTITUTE = 'FICPEF.PERSONAJUR';

//ID DE TIPO DE SOLICITUDES
const SOL_REP  = 1;
const SOL_INST = 2;

//ESTADOS
const ACTIVE   = 1;
const BLOCKED  = 2;
const INACTIVE = 3;

//TIPO DE PAGOS
const PAGO_CHEQUE   = 2;
const PAGO_DEPOSITO = 3;

//MOTIVO
const REASON_REGALO = 2;

//CUENTAS
const FONDO = 1;
const BANCO = 2;
const RETENCION = 3;
const GASTO = 4;


/*
|--------------------------------------------------------------------------
| TESTING
|--------------------------------------------------------------------------
*/

//MOTIVO DE LA SOLICITUD
const REEMBOLSO = 3;

//TIPO DE DOCUMENTO
const RECIBO_HONORARIO = 2;

//CODIGO DEL SISTEMA
const SISTEMA_SIM = 2;

//TIPO DE CLIENTE
const MEDICO = 1;
const INSTITUCION = 3;

/*
|--------------------------------------------------------------------------
| REPORT
|--------------------------------------------------------------------------
*/

const REPORT_TIME_LIMIT                         = 0; // idkc : 0 = ilimitado | 5*60 = 5 minutos
const REPORT_EXPORT_DIRECTORY                   = '/files/';

const REPORT_MESSAGE_DATASET_NOT_FOUND          = 'Lo sentimos. No se encontro DataSet configurados para generar reportes.';
const REPORT_MESSAGE_DATASET_NOT_FOUND_DATA     = 'Lo Sentimos. No se encuentra informacion del DataSet.';
const REPORT_MESSAGE_EXCEPTION                  = 'Oops! Hubo un inconveniente al procesar la información.';
const REPORT_MESSAGE_CREATE                     = 'Oops! No se encontro información de reporte para registrar.';
const REPORT_MESSAGE_EXPORT_GENERATE            = 'Oops! Hubo un inconveniente al procesar la informacion. Por Favor genera el reporte nuevamente';
const REPORT_MESSAGE_USER_REPORT_DATA_NOT_FOUND = 'No tiene reportes asignados';
const REPORT_DATA_NOT_FOUND                     = 'Lo sentimos, no se encontro información disponible';

/*
|--------------------------------------------------------------------------
| TABLA PARAMETRO -> ID
|--------------------------------------------------------------------------
*/
const ALERTA_TIEMPO_ESPERA_POR_DOCUMENTO = 1;
const ALERTA_TIEMPO_REGISTRO_GASTO 		 = 2;
const ALERTA_INSTITUCION_CLIENTE		 = 3;

const FONDO_SUBCATEGORIA_GERPROD = NULL;
const FONDO_SUBCATEGORIA_SUPERVISOR = 'S';
const FONDO_SUBCATEGORIA_INSTITUCION = 'I';
/*
|--------------------------------------------------------------------------
| EVENT 
|--------------------------------------------------------------------------
*/
const DATOS_INVALIDOS                         = 'Datos Invalidos: Complete todos los campos.';
const CREADO_SATISFACTORIAMENTE               = 'Creado satisfactoriamente.';
const DB_NOT_INSERT                           = 'Lo sentimos, Hubo un problema a la hora de guardar en la base de datos.';
const FILESTORAGE_DIR                         = 'uploads/';
const APP_ID                                  = 2;

const TITULO_INSTITUCIONAL 					  = 'FONDO INSTITUCIONAL';
const MONTO_DESCUENTO_PLANILLA                = 50;

const FONDO_AJUSTE = 1;
const FONDO_RETENCION = 2;
const FONDO_LIBERACION = 3;
const FONDO_DEPOSITO = 4;
const FONDO_DEVOLUCION = 5;
const FONDO_TRANSFERENCIA = 6;

const TIPO_FAMILIA = 129;
const TIPO_GERPROD = 128;

const INVERSION_MKT = 'M';
const INVERSION_INSTITUCIONAL = 'I';


define("TIMELINEHARD",serialize(array(
    'institucional'     =>  array(
        'o' => array(
            'title' =>  'Inicio Fondo Institucional',
            'info'  =>  ''
        )
    ),
    'toDepositTimeline' => array(
        '1' => array(
            'title' =>  'Validación Cont.',
            'info'  =>  'CONTABILIDAD'
        ),
        '2' => array(
            'title' =>  'Habilitación Fondo Inst.',
            'info'  =>  'ASISTENTE GERENCIA'
        )
    ),
    'depSeatTimeLine'   => array(
        '3' => array(
            'title' =>  'Deposito del Anticipo',
            'info'  =>  'TESORERÍA'
        ),
        '4' => array(
            'title' =>  'Asiento de Anticipo',
            'info'  =>  'CONTABILIDAD'
        )
    ),
    'registroGasto'     => array(
        '5' => array (
            'title' =>  'Reg. de Gastos',
            'info'  =>  'RESPONSABLE DEL GASTO'
        ),
        '6' => array(
            'title' =>  'Asiento de Diario',
            'info'  =>  'CONTABILIDAD'
        ),
    ),
    'reembolso'     =>  array(
        '7' => array(
            'title' =>  'Deposito del Reembolso',
            'info'  =>  'TESORERIA'
        )
    )
)));
