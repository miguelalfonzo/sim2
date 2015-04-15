<?php 

const FONDO_SUPERVISOR = 15;

const TERMINADO = 1;
const PDTE_DEPOSITO = 0;
const TIPO_ASIENTO_ANTICIPO = 'A';
const SOLICITUD_ASIENTO = 2;
const IMAGE_PATH = 'img/reembolso/';
const WIDTH = 800;
const HEIGHT = 600;
const data = 'Data';
const ok = 'Ok';
const warning = 'Warning';
const danger = 'Danger';
const error = 'Error';
const desc_error = 'System Error: Information in log file';
const status = 'Status';
const description = 'Description';
const DOCUMENTO_NO_SUSTENTABLE_ID = 7;
const EXPORTAR = 1;

const SOLES = 1 ;
const DOLARES = 2;
const CUENTA_SOLES = 1041100;
const CUENTA_DOLARES = 1041200;
const BANCOS = 'BANCOS';

const FONDO_DEPOSITADO = 1;
const FONDO_REGISTRADO = 1;

const CTA_FONDO_INSTITUCIONAL = 1413310;
const CTA_BANCOS_SOLES = 1041100;
const ASIENTO_FONDO_PENDIENTE = 0;
const ENABLE_DEPOSIT = 1;
const ASIENTO_FONDO = 2;
const ASIENTO_FONDO_GASTO = 3;

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


//TIPO GASTOS
const EXPENSE_SOLICITUDE = 'S';
const EXPENSE_FONDO = 'F';

//ESTADOS ADICIONALES
const ESTADO_DERIVADO = 'DERIVADO';
const ESTADO_R = 'RETENCION';
const ESTADO_DEPOSITADO = 'DEPOSITADO'; 
const ESTADO_AA = 'AD ANTICIPO';


const USER_CONTABILIDAD = 43;
const USER_GERENTE_COMERCIAL = 17;
const USER_TESORERIA = 42;

// EMAIL DE PRUEBAS
const POSTMAN_USER_EMAIL	= 'manueltemple@gmail.com';
const POSTMAN_USER_NAME		= 'Manuel Temple';
const SOPORTE_EMAIL         = 'jortiz@esinergy.com';

const R_PENDIENTE = 1;
const R_APROBADO = 2;
const R_REVISADO = 3;
const R_GASTO = 4;
const R_FINALIZADO = 5;
const R_NO_AUTORIZADO = 6;

const R_TODOS = 10;

const REP_MED = 'R';
const SUP = 'S';
const GER_PROD = 'P';
const GER_COM = 'G';
const CONT = 'C';
const TESORERIA = 'T';
const ASIS_GER = 'AG';

const TB_DOCTOR = 'FICPE.PERSONAFIS';
const TB_INSTITUTE = 'FICPEF.PERSONAJUR';

const SOLIC = 'S';
const INSTITUCIONAL = 'F';

const SOL_REP  = 1;
const SOL_INST = 2;

const ACTIVE   = 1;
const BLOCKED  = 2;
const INACTIVE = 3;

const PAGO_DEPOSITO = 3;
const PAGO_CHEQUE   = 2;

const REASON_REGALOS = 2;