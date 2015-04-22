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
const desc_error = 'System Error';
const status = 'Status';
const description = 'Description';
const DOCUMENTO_NO_SUSTENTABLE_ID = 7;

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
const CUENTA_REPARO_COMPRAS						= '6411000';
const CUENTA_REPARO_GOBIERNO					= '4011100';
const CUENTA_CONTRA_PARTE						= '1413360';
const ERROR_INVALID_ACCOUNT_MKT					= 'Error de Cuenta';
const MSG_INVALID_ACCOUNT_MKT					= 'Verificar campo de cuenta';


//ESTADOS
const ESTADO_GENERADO	= 7;

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
const REP_MED = 'R';
const SUP = 'S';
const GER_PROD = 'P';
const GER_COM = 'G';
const CONT = 'C';
const TESORERIA = 'T';
const ASIS_GER = 'AG';

//TABLAS EXTERNAS
const TB_DOCTOR = 'FICPE.PERSONAFIS';
const TB_INSTITUTE = 'FICPEF.PERSONAJUR';

//CODIGO DE SOLICITUDES
const SOLIC = 'S';
const INSTITUCIONAL = 'F';

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