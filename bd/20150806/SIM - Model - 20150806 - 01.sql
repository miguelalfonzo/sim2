--------------------------------------------------------
-- Archivo creado  - jueves-agosto-06-2015   
--------------------------------------------------------
DROP TABLE "ASIENTO" cascade constraints;
DROP TABLE "CUENTA" cascade constraints;
DROP TABLE "CUENTA_GASTO_MARCA" cascade constraints;
DROP TABLE "CUENTA_TIPO" cascade constraints;
DROP TABLE "DEPOSITO" cascade constraints;
DROP TABLE "DOCUMENTO" cascade constraints;
DROP TABLE "ESTADO" cascade constraints;
DROP TABLE "EVENT" cascade constraints;
DROP TABLE "FILE_STORAGE" cascade constraints;
DROP TABLE "FONDO_CATEGORIA" cascade constraints;
DROP TABLE "FONDO_CONTABLE" cascade constraints;
DROP TABLE "FONDO_GERENTE_PRODUCTO" cascade constraints;
DROP TABLE "FONDO_INSTITUCION" cascade constraints;
DROP TABLE "FONDO_MARKETING_HISTORIA" cascade constraints;
DROP TABLE "FONDO_SUBCATEGORIA" cascade constraints;
DROP TABLE "FONDO_SUPERVISOR" cascade constraints;
DROP TABLE "FONDO_TIPO" cascade constraints;
DROP TABLE "GASTO" cascade constraints;
DROP TABLE "GASTO_ITEM" cascade constraints;
DROP TABLE "INVERSION_ACTIVIDAD" cascade constraints;
DROP TABLE "INVERSION_POLITICA_APROBACION" cascade constraints;
DROP TABLE "MANTENIMIENTO" cascade constraints;
DROP TABLE "MARCA" cascade constraints;
DROP TABLE "MARCA_TIPO" cascade constraints;
DROP TABLE "MOTIVO" cascade constraints;
DROP TABLE "PARAMETRO" cascade constraints;
DROP TABLE "PERIODO" cascade constraints;
DROP TABLE "POLITICA_APROBACION" cascade constraints;
DROP TABLE "REPORTE_FORMULA" cascade constraints;
DROP TABLE "REPORTE_QUERY" cascade constraints;
DROP TABLE "REPORTE_USUARIO" cascade constraints;
DROP TABLE "SOLICITUD" cascade constraints;
DROP TABLE "SOLICITUD_CLIENTE" cascade constraints;
DROP TABLE "SOLICITUD_DETALLE" cascade constraints;
DROP TABLE "SOLICITUD_GERENTE" cascade constraints;
DROP TABLE "SOLICITUD_HISTORIAL" cascade constraints;
DROP TABLE "SOLICITUD_PRODUCTO" cascade constraints;
DROP TABLE "SOLICITUD_TIPO" cascade constraints;
DROP TABLE "SUB_ESTADO" cascade constraints;
DROP TABLE "TIEMPO_ESTIMADO_FLUJO" cascade constraints;
DROP TABLE "TIPO_ACTIVIDAD" cascade constraints;
DROP TABLE "TIPO_CLIENTE" cascade constraints;
DROP TABLE "TIPO_COMPROBANTE" cascade constraints;
DROP TABLE "TIPO_GASTO" cascade constraints;
DROP TABLE "TIPO_INVERSION" cascade constraints;
DROP TABLE "TIPO_MONEDA" cascade constraints;
DROP TABLE "TIPO_PAGO" cascade constraints;
DROP TABLE "TIPO_REGIMEN" cascade constraints;
DROP TABLE "USER_TEMPORAL" cascade constraints;
--------------------------------------------------------
--  DDL for Table ASIENTO
--------------------------------------------------------

  CREATE TABLE "ASIENTO" 
   (	"ID" NUMBER(*,0), 
	"NUM_CUENTA" NUMBER, 
	"CC" VARCHAR2(2), 
	"FEC_ORIGEN" DATE, 
	"IVA" VARCHAR2(2), 
	"COD_PRO" VARCHAR2(10), 
	"NOM_PROV" VARCHAR2(250), 
	"COD" VARCHAR2(10), 
	"RUC" VARCHAR2(11), 
	"PREFIJO" NUMBER(5,0), 
	"CBTE_PROV" NUMBER(20,0), 
	"D_C" CHAR(1), 
	"IMPORTE" FLOAT(126), 
	"LEYENDA" VARCHAR2(600), 
	"TIPO_RESP" VARCHAR2(1), 
	"ID_SOLICITUD" NUMBER, 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"TIPO_ASIENTO" CHAR(1), 
	"LEYENDA_FJ" VARCHAR2(10), 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_AT" DATE, 
	"DELETED_BY" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table CUENTA
--------------------------------------------------------

  CREATE TABLE "CUENTA" 
   (	"ID" NUMBER(*,0), 
	"NUM_CUENTA" NUMBER, 
	"IDTIPOCUENTA" NUMBER, 
	"IDTIPOMONEDA" NUMBER DEFAULT 1, 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table CUENTA_GASTO_MARCA
--------------------------------------------------------

  CREATE TABLE "CUENTA_GASTO_MARCA" 
   (	"ID" NUMBER, 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"IDDOCUMENTO" NUMBER, 
	"DELETED_AT" DATE, 
	"DELETED_BY" NUMBER, 
	"NUM_CUENTA_FONDO" NUMBER(*,0), 
	"NUM_CUENTA_GASTO" NUMBER(*,0), 
	"MARCA_CODIGO" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table CUENTA_TIPO
--------------------------------------------------------

  CREATE TABLE "CUENTA_TIPO" 
   (	"ID" NUMBER, 
	"NOMBRE" VARCHAR2(20), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table DEPOSITO
--------------------------------------------------------

  CREATE TABLE "DEPOSITO" 
   (	"ID" NUMBER(*,0), 
	"TOTAL" FLOAT(63), 
	"NUM_TRANSFERENCIA" VARCHAR2(200 CHAR), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"NUM_CUENTA" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table DOCUMENTO
--------------------------------------------------------

  CREATE TABLE "DOCUMENTO" 
   (	"ID" NUMBER, 
	"NOMBRE" VARCHAR2(20), 
	"CODIGO" CHAR(1), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_AT" DATE, 
	"DELETED_BY" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table ESTADO
--------------------------------------------------------

  CREATE TABLE "ESTADO" 
   (	"ID" NUMBER(*,0), 
	"NOMBRE" VARCHAR2(20), 
	"COLOR" VARCHAR2(10), 
	"DESCRIPCION" VARCHAR2(60), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table EVENT
--------------------------------------------------------

  CREATE TABLE "EVENT" 
   (	"ID" NUMBER, 
	"NAME" VARCHAR2(100), 
	"DESCRIPTION" VARCHAR2(250), 
	"PLACE" VARCHAR2(250), 
	"EVENT_DATE" VARCHAR2(250), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"SOLICITUD_ID" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table FILE_STORAGE
--------------------------------------------------------

  CREATE TABLE "FILE_STORAGE" 
   (	"ID" VARCHAR2(36), 
	"NAME" VARCHAR2(200), 
	"EXTENSION" VARCHAR2(10), 
	"DIRECTORY" VARCHAR2(300), 
	"APP" NUMBER(1,0), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"EVENT_ID" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table FONDO_CATEGORIA
--------------------------------------------------------

  CREATE TABLE "FONDO_CATEGORIA" 
   (	"ID" NUMBER, 
	"DESCRIPCION" VARCHAR2(200), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"POSITION" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table FONDO_CONTABLE
--------------------------------------------------------

  CREATE TABLE "FONDO_CONTABLE" 
   (	"ID" NUMBER, 
	"NOMBRE" VARCHAR2(100), 
	"CREATED_AT" DATE DEFAULT sysdate, 
	"UPDATED_AT" DATE DEFAULT sysdate, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_AT" DATE, 
	"DELETED_BY" NUMBER, 
	"IDTIPOFONDO" NUMBER DEFAULT 1, 
	"NUM_CUENTA" NUMBER, 
	"ID_MONEDA" NUMBER DEFAULT 1
   ) ;
--------------------------------------------------------
--  DDL for Table FONDO_GERENTE_PRODUCTO
--------------------------------------------------------

  CREATE TABLE "FONDO_GERENTE_PRODUCTO" 
   (	"ID" NUMBER, 
	"SUBCATEGORIA_ID" NUMBER, 
	"MARCA_ID" NUMBER, 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"SALDO" NUMBER, 
	"SALDO_NETO" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table FONDO_INSTITUCION
--------------------------------------------------------

  CREATE TABLE "FONDO_INSTITUCION" 
   (	"ID" NUMBER(*,0), 
	"SALDO" NUMBER, 
	"SUBCATEGORIA_ID" NUMBER(*,0), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER(*,0), 
	"UPDATED_BY" NUMBER(*,0), 
	"DELETED_BY" NUMBER(*,0), 
	"SALDO_NETO" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table FONDO_MARKETING_HISTORIA
--------------------------------------------------------

  CREATE TABLE "FONDO_MARKETING_HISTORIA" 
   (	"ID" NUMBER(*,0), 
	"ID_SOLICITUD" NUMBER(*,0), 
	"ID_FROM_FONDO" NUMBER(*,0), 
	"ID_TO_FONDO" NUMBER(*,0), 
	"FROM_OLD_SALDO" NUMBER, 
	"TO_OLD_SALDO" NUMBER, 
	"ID_FONDO_HISTORY_REASON" NUMBER(*,0), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"FROM_NEW_SALDO" NUMBER, 
	"TO_NEW_SALDO" NUMBER, 
	"ID_TIPO_FROM_FONDO" CHAR(1 CHAR), 
	"ID_TIPO_TO_FONDO" CHAR(1), 
	"FROM_OLD_SALDO_NETO" NUMBER, 
	"FROM_NEW_SALDO_NETO" NUMBER, 
	"TO_OLD_SALDO_NETO" NUMBER, 
	"TO_NEW_SALDO_NETO" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table FONDO_SUBCATEGORIA
--------------------------------------------------------

  CREATE TABLE "FONDO_SUBCATEGORIA" 
   (	"ID" NUMBER, 
	"DESCRIPCION" VARCHAR2(200), 
	"ID_FONDO_CATEGORIA" NUMBER, 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"POSITION" NUMBER, 
	"TIPO" CHAR(1)
   ) ;
--------------------------------------------------------
--  DDL for Table FONDO_SUPERVISOR
--------------------------------------------------------

  CREATE TABLE "FONDO_SUPERVISOR" 
   (	"ID" NUMBER, 
	"SUPERVISOR_ID" NUMBER, 
	"SUBCATEGORIA_ID" NUMBER, 
	"MARCA_ID" NUMBER, 
	"SALDO" NUMBER, 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"SALDO_NETO" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table FONDO_TIPO
--------------------------------------------------------

  CREATE TABLE "FONDO_TIPO" 
   (	"ID" NUMBER, 
	"NOMBRE" VARCHAR2(30), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table GASTO
--------------------------------------------------------

  CREATE TABLE "GASTO" 
   (	"ID" NUMBER(*,0), 
	"IDCOMPROBANTE" NUMBER(1,0), 
	"RUC" NUMBER(11,0), 
	"RAZON" VARCHAR2(500 CHAR), 
	"NUM_PREFIJO" VARCHAR2(4), 
	"NUM_SERIE" NUMBER(12,0), 
	"FECHA_MOVIMIENTO" DATE, 
	"DESCRIPCION" VARCHAR2(100 CHAR), 
	"SUB_TOT" FLOAT(63), 
	"IMP_SERV" FLOAT(63), 
	"IGV" FLOAT(63), 
	"MONTO" FLOAT(63), 
	"IDIGV" FLOAT(126), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"REPARO" CHAR(1) DEFAULT 0, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"ID_SOLICITUD" NUMBER, 
	"MONTO_TRIBUTO" FLOAT(63), 
	"IDTIPOTRIBUTO" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table GASTO_ITEM
--------------------------------------------------------

  CREATE TABLE "GASTO_ITEM" 
   (	"ID_GASTO" NUMBER(10,0), 
	"CANTIDAD" NUMBER(5,0), 
	"DESCRIPCION" VARCHAR2(100), 
	"TIPO_GASTO" NUMBER(10,0), 
	"IMPORTE" FLOAT(63), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"ID" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table INVERSION_ACTIVIDAD
--------------------------------------------------------

  CREATE TABLE "INVERSION_ACTIVIDAD" 
   (	"ID" NUMBER, 
	"ID_INVERSION" NUMBER, 
	"ID_ACTIVIDAD" NUMBER, 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table INVERSION_POLITICA_APROBACION
--------------------------------------------------------

  CREATE TABLE "INVERSION_POLITICA_APROBACION" 
   (	"ID" NUMBER(*,0), 
	"ID_INVERSION" NUMBER(*,0), 
	"ID_POLITICA_APROBACION" NUMBER(*,0), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER(*,0), 
	"UPDATED_BY" NUMBER(*,0), 
	"DELETED_BY" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table MANTENIMIENTO
--------------------------------------------------------

  CREATE TABLE "MANTENIMIENTO" 
   (	"ID" NUMBER, 
	"DESCRIPCION" VARCHAR2(60), 
	"FORMULA" NVARCHAR2(1000)
   ) ;
--------------------------------------------------------
--  DDL for Table MARCA
--------------------------------------------------------

  CREATE TABLE "MARCA" 
   (	"ID" NUMBER, 
	"CODIGO" NUMBER, 
	"ID_TIPO_MARCA" NUMBER, 
	"CREATED_AT" VARCHAR2(20), 
	"UPDATED_AT" VARCHAR2(20), 
	"CREATED_BY" VARCHAR2(20), 
	"UPDATED_BY" VARCHAR2(20), 
	"DELETED_AT" DATE, 
	"DELETED_BY" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table MARCA_TIPO
--------------------------------------------------------

  CREATE TABLE "MARCA_TIPO" 
   (	"ID" NUMBER, 
	"NOMBRE" VARCHAR2(20), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table MOTIVO
--------------------------------------------------------

  CREATE TABLE "MOTIVO" 
   (	"ID" NUMBER(2,0), 
	"NOMBRE" VARCHAR2(40), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table PARAMETRO
--------------------------------------------------------

  CREATE TABLE "PARAMETRO" 
   (	"ID" NUMBER, 
	"DESCRIPCION" VARCHAR2(60), 
	"VALOR" NUMBER, 
	"MENSAJE" VARCHAR2(300), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table PERIODO
--------------------------------------------------------

  CREATE TABLE "PERIODO" 
   (	"ID" NUMBER, 
	"ANIOMES" VARCHAR2(6), 
	"STATUS" NUMBER, 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"IDTIPOSOLICITUD" VARCHAR2(20)
   ) ;
--------------------------------------------------------
--  DDL for Table POLITICA_APROBACION
--------------------------------------------------------

  CREATE TABLE "POLITICA_APROBACION" 
   (	"ID" NUMBER, 
	"DESDE" NUMBER, 
	"HASTA" NUMBER, 
	"ORDEN" NUMBER, 
	"TIPO_USUARIO" VARCHAR2(2 CHAR), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table REPORTE_FORMULA
--------------------------------------------------------

  CREATE TABLE "REPORTE_FORMULA" 
   (	"ID_REPORTE" NUMBER(*,0), 
	"DESCRIPCION" VARCHAR2(50), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"QUERY_ID" NUMBER, 
	"FORMULA" CLOB
   ) ;
--------------------------------------------------------
--  DDL for Table REPORTE_QUERY
--------------------------------------------------------

  CREATE TABLE "REPORTE_QUERY" 
   (	"ID" NUMBER, 
	"NAME" VARCHAR2(50), 
	"QUERY" CLOB
   ) ;
--------------------------------------------------------
--  DDL for Table REPORTE_USUARIO
--------------------------------------------------------

  CREATE TABLE "REPORTE_USUARIO" 
   (	"ID" NUMBER(*,0), 
	"ID_REPORTE" NUMBER(*,0), 
	"ID_USUARIO" NUMBER(*,0), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE
   ) ;
--------------------------------------------------------
--  DDL for Table SOLICITUD
--------------------------------------------------------

  CREATE TABLE "SOLICITUD" 
   (	"ID" NUMBER, 
	"TITULO" VARCHAR2(100), 
	"DESCRIPCION" VARCHAR2(200), 
	"OBSERVACION" VARCHAR2(200), 
	"ID_ESTADO" NUMBER, 
	"ID_ACTIVIDAD" NUMBER, 
	"IDTIPOSOLICITUD" NUMBER, 
	"TOKEN" VARCHAR2(50), 
	"STATUS" NUMBER DEFAULT 1, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"ID_DETALLE" NUMBER, 
	"ANOTACION" VARCHAR2(200), 
	"ID_INVERSION" NUMBER, 
	"ID_USER_ASSIGN" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table SOLICITUD_CLIENTE
--------------------------------------------------------

  CREATE TABLE "SOLICITUD_CLIENTE" 
   (	"ID" NUMBER(10,0), 
	"ID_SOLICITUD" NUMBER(10,0), 
	"ID_CLIENTE" NUMBER(10,0), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"ID_TIPO_CLIENTE" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table SOLICITUD_DETALLE
--------------------------------------------------------

  CREATE TABLE "SOLICITUD_DETALLE" 
   (	"DETALLE" VARCHAR2(2700), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"ID_MONEDA" NUMBER, 
	"ID_MOTIVO" NUMBER, 
	"ID_PAGO" NUMBER, 
	"ID_FONDO" NUMBER, 
	"ID" NUMBER, 
	"ID_DEPOSITO" NUMBER, 
	"ID_PERIODO" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table SOLICITUD_GERENTE
--------------------------------------------------------

  CREATE TABLE "SOLICITUD_GERENTE" 
   (	"ID" NUMBER(10,0), 
	"ID_SOLICITUD" NUMBER(10,0), 
	"ID_GERPROD" NUMBER(10,0), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"STATUS" NUMBER(1,0) DEFAULT 0, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"TIPO_USUARIO" VARCHAR2(2), 
	"PERMISO" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table SOLICITUD_HISTORIAL
--------------------------------------------------------

  CREATE TABLE "SOLICITUD_HISTORIAL" 
   (	"ID" NUMBER(*,0), 
	"STATUS_FROM" NUMBER DEFAULT NULL, 
	"STATUS_TO" NUMBER, 
	"USER_FROM" VARCHAR2(3), 
	"USER_TO" VARCHAR2(3), 
	"ID_SOLICITUD" NUMBER(10,0), 
	"CREATED_AT" DATE, 
	"NOTIFIED" NUMBER(1,0) DEFAULT 0, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table SOLICITUD_PRODUCTO
--------------------------------------------------------

  CREATE TABLE "SOLICITUD_PRODUCTO" 
   (	"ID" NUMBER(10,0), 
	"ID_SOLICITUD" NUMBER(10,0), 
	"ID_PRODUCTO" NUMBER(10,0), 
	"MONTO_ASIGNADO" FLOAT(63), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"ID_FONDO_MARKETING" NUMBER(*,0), 
	"ID_TIPO_FONDO_MARKETING" VARCHAR2(2 CHAR)
   ) ;
--------------------------------------------------------
--  DDL for Table SOLICITUD_TIPO
--------------------------------------------------------

  CREATE TABLE "SOLICITUD_TIPO" 
   (	"ID" NUMBER(2,0), 
	"NOMBRE" VARCHAR2(30), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"CODE" CHAR(1)
   ) ;
--------------------------------------------------------
--  DDL for Table SUB_ESTADO
--------------------------------------------------------

  CREATE TABLE "SUB_ESTADO" 
   (	"ID" NUMBER(2,0), 
	"NOMBRE" VARCHAR2(45 CHAR), 
	"COLOR" VARCHAR2(15), 
	"DESCRIPCION" VARCHAR2(150), 
	"ID_ESTADO" NUMBER(2,0) DEFAULT 1, 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"DESCRIPCION_MIN" VARCHAR2(50)
   ) ;
--------------------------------------------------------
--  DDL for Table TIEMPO_ESTIMADO_FLUJO
--------------------------------------------------------

  CREATE TABLE "TIEMPO_ESTIMADO_FLUJO" 
   (	"ID" NUMBER(*,0), 
	"STATUS_ID" NUMBER(*,0), 
	"TO_USER_TYPE" VARCHAR2(3), 
	"HOURS" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table TIPO_ACTIVIDAD
--------------------------------------------------------

  CREATE TABLE "TIPO_ACTIVIDAD" 
   (	"ID" NUMBER(2,0), 
	"NOMBRE" VARCHAR2(40), 
	"COLOR" VARCHAR2(10), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"TIPO_CLIENTE" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table TIPO_CLIENTE
--------------------------------------------------------

  CREATE TABLE "TIPO_CLIENTE" 
   (	"ID" NUMBER, 
	"DESCRIPCION" VARCHAR2(25), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"NUM_MEDICO" NUMBER, 
	"RELACION" VARCHAR2(20)
   ) ;
--------------------------------------------------------
--  DDL for Table TIPO_COMPROBANTE
--------------------------------------------------------

  CREATE TABLE "TIPO_COMPROBANTE" 
   (	"ID" NUMBER, 
	"DESCRIPCION" VARCHAR2(30), 
	"CTA_SUNAT" VARCHAR2(3 CHAR), 
	"MARCA" VARCHAR2(10), 
	"IGV" NUMBER(1,0), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table TIPO_GASTO
--------------------------------------------------------

  CREATE TABLE "TIPO_GASTO" 
   (	"ID" NUMBER(10,0), 
	"DESCRIPCION" VARCHAR2(100), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table TIPO_INVERSION
--------------------------------------------------------

  CREATE TABLE "TIPO_INVERSION" 
   (	"ID" NUMBER, 
	"NOMBRE" VARCHAR2(45), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"CODIGO_ACTIVIDAD" CHAR(1 CHAR), 
	"ID_FONDO_CONTABLE" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TIPO_MONEDA
--------------------------------------------------------

  CREATE TABLE "TIPO_MONEDA" 
   (	"ID" NUMBER(1,0), 
	"DESCRIPCION" VARCHAR2(10), 
	"SIMBOLO" VARCHAR2(4), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE
   ) ;
--------------------------------------------------------
--  DDL for Table TIPO_PAGO
--------------------------------------------------------

  CREATE TABLE "TIPO_PAGO" 
   (	"ID" NUMBER(10,0), 
	"NOMBRE" VARCHAR2(30), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE
   ) ;
--------------------------------------------------------
--  DDL for Table TIPO_REGIMEN
--------------------------------------------------------

  CREATE TABLE "TIPO_REGIMEN" 
   (	"ID" NUMBER, 
	"DESCRIPCION" VARCHAR2(50), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table USER_TEMPORAL
--------------------------------------------------------

  CREATE TABLE "USER_TEMPORAL" 
   (	"ID" NUMBER, 
	"ID_USER" NUMBER, 
	"ID_USER_TEMP" NUMBER, 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Index ASIENTO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "ASIENTO_PK" ON "ASIENTO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index CUENTA_GASTO_MARC_UK2
--------------------------------------------------------

  CREATE UNIQUE INDEX "CUENTA_GASTO_MARC_UK2" ON "CUENTA_GASTO_MARCA" ("NUM_CUENTA_FONDO", "NUM_CUENTA_GASTO", "MARCA_CODIGO", "IDDOCUMENTO") 
  ;
--------------------------------------------------------
--  DDL for Index CUENTA_MARCA_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "CUENTA_MARCA_PK" ON "CUENTA_GASTO_MARCA" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index CUENTA_TIPO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "CUENTA_TIPO_PK" ON "CUENTA_TIPO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index DEPOSITO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "DEPOSITO_PK" ON "DEPOSITO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index DMKT2_RG_CUENTA_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "DMKT2_RG_CUENTA_PK" ON "CUENTA" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index DMKT2_RG_CUENTA_UK
--------------------------------------------------------

  CREATE UNIQUE INDEX "DMKT2_RG_CUENTA_UK" ON "CUENTA" ("NUM_CUENTA") 
  ;
--------------------------------------------------------
--  DDL for Index DOCUMENTO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "DOCUMENTO_PK" ON "DOCUMENTO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index DOCUMENTO_UK
--------------------------------------------------------

  CREATE UNIQUE INDEX "DOCUMENTO_UK" ON "DOCUMENTO" ("CODIGO") 
  ;
--------------------------------------------------------
--  DDL for Index ESTADO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "ESTADO_PK" ON "SUB_ESTADO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index ESTADO_RANGO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "ESTADO_RANGO_PK" ON "ESTADO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index EVENT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "EVENT_PK" ON "EVENT" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FILE_STORAGE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "FILE_STORAGE_PK" ON "FILE_STORAGE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDO_INSTITUCION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "FONDO_INSTITUCION_PK" ON "FONDO_INSTITUCION" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDO_INSTITUCION_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "FONDO_INSTITUCION_UK1" ON "FONDO_INSTITUCION" ("SUBCATEGORIA_ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDO_MARKETING_HISTORIA_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "FONDO_MARKETING_HISTORIA_PK" ON "FONDO_MARKETING_HISTORIA" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "FONDO_PK" ON "FONDO_CONTABLE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDOS_CATEGORIAS_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "FONDOS_CATEGORIAS_PK" ON "FONDO_CATEGORIA" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDOS_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "FONDOS_PK" ON "FONDO_GERENTE_PRODUCTO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDOS_SUBCATEGORIAS_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "FONDOS_SUBCATEGORIAS_PK" ON "FONDO_SUBCATEGORIA" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDOS_SUPERVISOR_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "FONDOS_SUPERVISOR_PK" ON "FONDO_SUPERVISOR" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDOS_SUPERVISOR_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "FONDOS_SUPERVISOR_UK1" ON "FONDO_SUPERVISOR" ("SUPERVISOR_ID", "MARCA_ID", "SUBCATEGORIA_ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDOS_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "FONDOS_UK1" ON "FONDO_GERENTE_PRODUCTO" ("SUBCATEGORIA_ID", "MARCA_ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDO_TIPO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "FONDO_TIPO_PK" ON "FONDO_TIPO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDO_UK2
--------------------------------------------------------

  CREATE UNIQUE INDEX "FONDO_UK2" ON "FONDO_CONTABLE" ("NUM_CUENTA") 
  ;
--------------------------------------------------------
--  DDL for Index GASTO_ITEM_PK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "GASTO_ITEM_PK1" ON "GASTO_ITEM" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index GASTO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "GASTO_PK" ON "GASTO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index ID_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "ID_PK" ON "TIPO_ACTIVIDAD" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index INVERSION_ACTIVIDAD_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "INVERSION_ACTIVIDAD_PK" ON "INVERSION_ACTIVIDAD" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index INVERSION_ACTIVIDAD_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "INVERSION_ACTIVIDAD_UK1" ON "INVERSION_ACTIVIDAD" ("ID_INVERSION", "ID_ACTIVIDAD") 
  ;
--------------------------------------------------------
--  DDL for Index INVERSION_POLITICA_APROBAC_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "INVERSION_POLITICA_APROBAC_PK" ON "INVERSION_POLITICA_APROBACION" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index INVERSION_POLITICA_APROBA_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "INVERSION_POLITICA_APROBA_UK1" ON "INVERSION_POLITICA_APROBACION" ("ID_INVERSION", "ID_POLITICA_APROBACION") 
  ;
--------------------------------------------------------
--  DDL for Index MANTENIMIENTO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "MANTENIMIENTO_PK" ON "MANTENIMIENTO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index MARCA_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "MARCA_PK" ON "MARCA" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index MARCA_TIPO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "MARCA_TIPO_PK" ON "MARCA_TIPO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index PARAMETRO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "PARAMETRO_PK" ON "PARAMETRO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index PERIODO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "PERIODO_PK" ON "PERIODO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index PK_TIPOGASTO
--------------------------------------------------------

  CREATE UNIQUE INDEX "PK_TIPOGASTO" ON "TIPO_GASTO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index PK_TIPO_MONEDA
--------------------------------------------------------

  CREATE UNIQUE INDEX "PK_TIPO_MONEDA" ON "TIPO_MONEDA" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index POLITICA_APROBACION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "POLITICA_APROBACION_PK" ON "POLITICA_APROBACION" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index POLITICA_APROBACION_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "POLITICA_APROBACION_UK1" ON "POLITICA_APROBACION" ("DESDE", "HASTA", "ORDEN", "TIPO_USUARIO") 
  ;
--------------------------------------------------------
--  DDL for Index REPORTE_FORMULA_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "REPORTE_FORMULA_PK" ON "REPORTE_FORMULA" ("ID_REPORTE") 
  ;
--------------------------------------------------------
--  DDL for Index REPORTE_QUERY_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "REPORTE_QUERY_PK" ON "REPORTE_QUERY" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index REPORTE_USUARIO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "REPORTE_USUARIO_PK" ON "REPORTE_USUARIO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index RETENCION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "RETENCION_PK" ON "TIPO_REGIMEN" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_CLIENTE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SOLICITUD_CLIENTE_PK" ON "SOLICITUD_CLIENTE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_CLIENTE_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "SOLICITUD_CLIENTE_UK1" ON "SOLICITUD_CLIENTE" ("ID_SOLICITUD", "ID_CLIENTE", "ID_TIPO_CLIENTE") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_DERIVADO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SOLICITUD_DERIVADO_PK" ON "SOLICITUD_GERENTE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_DETALLE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SOLICITUD_DETALLE_PK" ON "SOLICITUD_DETALLE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_DETALLE_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "SOLICITUD_DETALLE_UK1" ON "SOLICITUD_DETALLE" ("ID_DEPOSITO") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_FAMILIA_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SOLICITUD_FAMILIA_PK" ON "SOLICITUD_PRODUCTO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_HISTORIAL_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SOLICITUD_HISTORIAL_PK" ON "SOLICITUD_HISTORIAL" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SOLICITUD_PK" ON "SOLICITUD" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_TIPO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SOLICITUD_TIPO_PK" ON "SOLICITUD_TIPO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_TIPO_UK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SOLICITUD_TIPO_UK" ON "SOLICITUD_TIPO" ("CODE") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "SOLICITUD_UK1" ON "SOLICITUD" ("TOKEN") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_UK2
--------------------------------------------------------

  CREATE UNIQUE INDEX "SOLICITUD_UK2" ON "SOLICITUD" ("ID_DETALLE") 
  ;
--------------------------------------------------------
--  DDL for Index TIEMPO_ESTIMADO_FLUJO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "TIEMPO_ESTIMADO_FLUJO_PK" ON "TIEMPO_ESTIMADO_FLUJO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TIPO_CLIENTE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "TIPO_CLIENTE_PK" ON "TIPO_CLIENTE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TIPO_COMPROBANTE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "TIPO_COMPROBANTE_PK" ON "TIPO_COMPROBANTE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TIPO_INVERSION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "TIPO_INVERSION_PK" ON "TIPO_INVERSION" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TIPO_PAGO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "TIPO_PAGO_PK" ON "TIPO_PAGO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TIPO_SOLICITUD_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "TIPO_SOLICITUD_PK" ON "MOTIVO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index USER_TEMPORAL_ID_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "USER_TEMPORAL_ID_PK" ON "USER_TEMPORAL" ("ID") 
  ;
--------------------------------------------------------
--  Constraints for Table ASIENTO
--------------------------------------------------------

  ALTER TABLE "ASIENTO" MODIFY ("TIPO_ASIENTO" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" ADD CONSTRAINT "ASIENTO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "ASIENTO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" MODIFY ("UPDATED_BY" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" MODIFY ("CREATED_BY" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" MODIFY ("IMPORTE" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" MODIFY ("D_C" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" MODIFY ("FEC_ORIGEN" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" MODIFY ("NUM_CUENTA" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" MODIFY ("ID_SOLICITUD" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table CUENTA
--------------------------------------------------------

  ALTER TABLE "CUENTA" ADD CONSTRAINT "DMKT2_RG_CUENTA_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "CUENTA" ADD CONSTRAINT "DMKT2_RG_CUENTA_UK" UNIQUE ("NUM_CUENTA") ENABLE;
  ALTER TABLE "CUENTA" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "CUENTA" MODIFY ("NUM_CUENTA" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table CUENTA_GASTO_MARCA
--------------------------------------------------------

  ALTER TABLE "CUENTA_GASTO_MARCA" ADD CONSTRAINT "CUENTA_GASTO_MARC_UK2" UNIQUE ("NUM_CUENTA_FONDO", "NUM_CUENTA_GASTO", "MARCA_CODIGO", "IDDOCUMENTO") ENABLE;
  ALTER TABLE "CUENTA_GASTO_MARCA" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "CUENTA_GASTO_MARCA" ADD CONSTRAINT "CUENTA_MARCA_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table CUENTA_TIPO
--------------------------------------------------------

  ALTER TABLE "CUENTA_TIPO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "CUENTA_TIPO" ADD CONSTRAINT "CUENTA_TIPO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table DEPOSITO
--------------------------------------------------------

  ALTER TABLE "DEPOSITO" MODIFY ("NUM_CUENTA" NOT NULL ENABLE);
  ALTER TABLE "DEPOSITO" MODIFY ("NUM_TRANSFERENCIA" NOT NULL ENABLE);
  ALTER TABLE "DEPOSITO" ADD CONSTRAINT "PK_CODDEPOSITO" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "DEPOSITO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "DEPOSITO" MODIFY ("TOTAL" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table DOCUMENTO
--------------------------------------------------------

  ALTER TABLE "DOCUMENTO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "DOCUMENTO" MODIFY ("CODIGO" NOT NULL ENABLE);
  ALTER TABLE "DOCUMENTO" ADD CONSTRAINT "DOCUMENTO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "DOCUMENTO" ADD CONSTRAINT "DOCUMENTO_UK" UNIQUE ("CODIGO") ENABLE;
--------------------------------------------------------
--  Constraints for Table ESTADO
--------------------------------------------------------

  ALTER TABLE "ESTADO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "ESTADO" ADD CONSTRAINT "ESTADO_RANGO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table EVENT
--------------------------------------------------------

  ALTER TABLE "EVENT" MODIFY ("EVENT_DATE" NOT NULL ENABLE);
  ALTER TABLE "EVENT" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "EVENT" MODIFY ("NAME" NOT NULL ENABLE);
  ALTER TABLE "EVENT" MODIFY ("DESCRIPTION" NOT NULL ENABLE);
  ALTER TABLE "EVENT" ADD CONSTRAINT "EVENT_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table FILE_STORAGE
--------------------------------------------------------

  ALTER TABLE "FILE_STORAGE" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "FILE_STORAGE" MODIFY ("NAME" NOT NULL ENABLE);
  ALTER TABLE "FILE_STORAGE" MODIFY ("EXTENSION" NOT NULL ENABLE);
  ALTER TABLE "FILE_STORAGE" MODIFY ("DIRECTORY" NOT NULL ENABLE);
  ALTER TABLE "FILE_STORAGE" MODIFY ("APP" NOT NULL ENABLE);
  ALTER TABLE "FILE_STORAGE" ADD CONSTRAINT "FILE_STORAGE_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table FONDO_CATEGORIA
--------------------------------------------------------

  ALTER TABLE "FONDO_CATEGORIA" ADD CONSTRAINT "FONDOS_CATEGORIAS_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "FONDO_CATEGORIA" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FONDO_CONTABLE
--------------------------------------------------------

  ALTER TABLE "FONDO_CONTABLE" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_CONTABLE" ADD CONSTRAINT "FONDO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table FONDO_GERENTE_PRODUCTO
--------------------------------------------------------

  ALTER TABLE "FONDO_GERENTE_PRODUCTO" ADD CONSTRAINT "FONDOS_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "FONDO_GERENTE_PRODUCTO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_GERENTE_PRODUCTO" MODIFY ("MARCA_ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_GERENTE_PRODUCTO" MODIFY ("SUBCATEGORIA_ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_GERENTE_PRODUCTO" ADD CONSTRAINT "FONDOS_UK1" UNIQUE ("SUBCATEGORIA_ID", "MARCA_ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table FONDO_INSTITUCION
--------------------------------------------------------

  ALTER TABLE "FONDO_INSTITUCION" ADD CONSTRAINT "FONDO_INSTITUCION_UK1" UNIQUE ("SUBCATEGORIA_ID") ENABLE;
  ALTER TABLE "FONDO_INSTITUCION" ADD CONSTRAINT "FONDO_INSTITUCION_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "FONDO_INSTITUCION" MODIFY ("SUBCATEGORIA_ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_INSTITUCION" MODIFY ("SALDO" NOT NULL ENABLE);
  ALTER TABLE "FONDO_INSTITUCION" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FONDO_MARKETING_HISTORIA
--------------------------------------------------------

  ALTER TABLE "FONDO_MARKETING_HISTORIA" MODIFY ("ID_FONDO_HISTORY_REASON" NOT NULL ENABLE);
  ALTER TABLE "FONDO_MARKETING_HISTORIA" ADD CONSTRAINT "FONDO_MARKETING_HISTORIA_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "FONDO_MARKETING_HISTORIA" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FONDO_SUBCATEGORIA
--------------------------------------------------------

  ALTER TABLE "FONDO_SUBCATEGORIA" ADD CONSTRAINT "FONDOS_SUBCATEGORIAS_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "FONDO_SUBCATEGORIA" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_SUBCATEGORIA" MODIFY ("ID_FONDO_CATEGORIA" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FONDO_SUPERVISOR
--------------------------------------------------------

  ALTER TABLE "FONDO_SUPERVISOR" ADD CONSTRAINT "FONDOS_SUPERVISOR_UK1" UNIQUE ("SUPERVISOR_ID", "MARCA_ID", "SUBCATEGORIA_ID") ENABLE;
  ALTER TABLE "FONDO_SUPERVISOR" ADD CONSTRAINT "FONDOS_SUPERVISOR_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "FONDO_SUPERVISOR" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_SUPERVISOR" MODIFY ("MARCA_ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_SUPERVISOR" MODIFY ("SUBCATEGORIA_ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_SUPERVISOR" MODIFY ("SUPERVISOR_ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FONDO_TIPO
--------------------------------------------------------

  ALTER TABLE "FONDO_TIPO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_TIPO" ADD CONSTRAINT "FONDO_TIPO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table GASTO
--------------------------------------------------------

  ALTER TABLE "GASTO" ADD CONSTRAINT "PK_ID_GASTO" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "GASTO" MODIFY ("ID_SOLICITUD" NOT NULL ENABLE);
  ALTER TABLE "GASTO" MODIFY ("IDCOMPROBANTE" NOT NULL ENABLE);
  ALTER TABLE "GASTO" ADD CONSTRAINT "GASTO_CHK2" CHECK (NUM_SERIE >= 1) ENABLE;
--------------------------------------------------------
--  Constraints for Table GASTO_ITEM
--------------------------------------------------------

  ALTER TABLE "GASTO_ITEM" MODIFY ("ID_GASTO" NOT NULL ENABLE);
  ALTER TABLE "GASTO_ITEM" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "GASTO_ITEM" ADD CONSTRAINT "GASTO_ITEM_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "GASTO_ITEM" MODIFY ("IMPORTE" NOT NULL ENABLE);
  ALTER TABLE "GASTO_ITEM" MODIFY ("TIPO_GASTO" NOT NULL ENABLE);
  ALTER TABLE "GASTO_ITEM" MODIFY ("CANTIDAD" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table INVERSION_ACTIVIDAD
--------------------------------------------------------

  ALTER TABLE "INVERSION_ACTIVIDAD" ADD CONSTRAINT "INVERSION_ACTIVIDAD_UK1" UNIQUE ("ID_INVERSION", "ID_ACTIVIDAD") ENABLE;
  ALTER TABLE "INVERSION_ACTIVIDAD" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "INVERSION_ACTIVIDAD" MODIFY ("ID_ACTIVIDAD" NOT NULL ENABLE);
  ALTER TABLE "INVERSION_ACTIVIDAD" MODIFY ("ID_INVERSION" NOT NULL ENABLE);
  ALTER TABLE "INVERSION_ACTIVIDAD" ADD CONSTRAINT "INVERSION_ACTIVIDAD_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table INVERSION_POLITICA_APROBACION
--------------------------------------------------------

  ALTER TABLE "INVERSION_POLITICA_APROBACION" ADD CONSTRAINT "INVERSION_POLITICA_APROBA_UK1" UNIQUE ("ID_INVERSION", "ID_POLITICA_APROBACION") ENABLE;
  ALTER TABLE "INVERSION_POLITICA_APROBACION" ADD CONSTRAINT "INVERSION_POLITICA_APROBAC_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "INVERSION_POLITICA_APROBACION" MODIFY ("ID_POLITICA_APROBACION" NOT NULL ENABLE);
  ALTER TABLE "INVERSION_POLITICA_APROBACION" MODIFY ("ID_INVERSION" NOT NULL ENABLE);
  ALTER TABLE "INVERSION_POLITICA_APROBACION" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table MANTENIMIENTO
--------------------------------------------------------

  ALTER TABLE "MANTENIMIENTO" ADD CONSTRAINT "MANTENIMIENTO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "MANTENIMIENTO" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table MARCA
--------------------------------------------------------

  ALTER TABLE "MARCA" MODIFY ("CODIGO" NOT NULL ENABLE);
  ALTER TABLE "MARCA" ADD CONSTRAINT "MARCA_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "MARCA" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table MARCA_TIPO
--------------------------------------------------------

  ALTER TABLE "MARCA_TIPO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "MARCA_TIPO" ADD CONSTRAINT "MARCA_TIPO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table MOTIVO
--------------------------------------------------------

  ALTER TABLE "MOTIVO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "MOTIVO" ADD CONSTRAINT "TIPO_SOLICITUD_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table PARAMETRO
--------------------------------------------------------

  ALTER TABLE "PARAMETRO" ADD CONSTRAINT "PARAMETRO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "PARAMETRO" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table PERIODO
--------------------------------------------------------

  ALTER TABLE "PERIODO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "PERIODO" ADD CONSTRAINT "PERIODO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table POLITICA_APROBACION
--------------------------------------------------------

  ALTER TABLE "POLITICA_APROBACION" ADD CONSTRAINT "POLITICA_APROBACION_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "POLITICA_APROBACION" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "POLITICA_APROBACION" ADD CONSTRAINT "POLITICA_APROBACION_UK1" UNIQUE ("DESDE", "HASTA", "ORDEN", "TIPO_USUARIO") ENABLE;
  ALTER TABLE "POLITICA_APROBACION" MODIFY ("TIPO_USUARIO" NOT NULL ENABLE);
  ALTER TABLE "POLITICA_APROBACION" MODIFY ("ORDEN" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table REPORTE_FORMULA
--------------------------------------------------------

  ALTER TABLE "REPORTE_FORMULA" MODIFY ("ID_REPORTE" NOT NULL ENABLE);
  ALTER TABLE "REPORTE_FORMULA" ADD CONSTRAINT "REPORTE_FORMULA_PK" PRIMARY KEY ("ID_REPORTE") ENABLE;
--------------------------------------------------------
--  Constraints for Table REPORTE_QUERY
--------------------------------------------------------

  ALTER TABLE "REPORTE_QUERY" ADD CONSTRAINT "REPORTE_QUERY_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "REPORTE_QUERY" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table REPORTE_USUARIO
--------------------------------------------------------

  ALTER TABLE "REPORTE_USUARIO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "REPORTE_USUARIO" ADD CONSTRAINT "REPORTE_USUARIO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table SOLICITUD
--------------------------------------------------------

  ALTER TABLE "SOLICITUD" ADD CONSTRAINT "SOLICITUD_UK1" UNIQUE ("TOKEN") ENABLE;
  ALTER TABLE "SOLICITUD" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD" ADD CONSTRAINT "SOLICITUD_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SOLICITUD" ADD CONSTRAINT "SOLICITUD_UK2" UNIQUE ("ID_DETALLE") ENABLE;
  ALTER TABLE "SOLICITUD" MODIFY ("ID_DETALLE" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SOLICITUD_CLIENTE
--------------------------------------------------------

  ALTER TABLE "SOLICITUD_CLIENTE" ADD CONSTRAINT "SOLICITUD_CLIENTE_UK1" UNIQUE ("ID_SOLICITUD", "ID_CLIENTE", "ID_TIPO_CLIENTE") ENABLE;
  ALTER TABLE "SOLICITUD_CLIENTE" MODIFY ("ID_TIPO_CLIENTE" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_CLIENTE" MODIFY ("ID_CLIENTE" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_CLIENTE" ADD CONSTRAINT "SOLICITUD_CLIENTE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SOLICITUD_CLIENTE" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_CLIENTE" MODIFY ("ID_SOLICITUD" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SOLICITUD_DETALLE
--------------------------------------------------------

  ALTER TABLE "SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_UK1" UNIQUE ("ID_DEPOSITO") ENABLE;
  ALTER TABLE "SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SOLICITUD_DETALLE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SOLICITUD_GERENTE
--------------------------------------------------------

  ALTER TABLE "SOLICITUD_GERENTE" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_GERENTE" ADD CONSTRAINT "SOLICITUD_DERIVADO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table SOLICITUD_HISTORIAL
--------------------------------------------------------

  ALTER TABLE "SOLICITUD_HISTORIAL" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_HISTORIAL" ADD CONSTRAINT "SOLICITUD_HISTORIAL_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SOLICITUD_HISTORIAL" MODIFY ("UPDATED_BY" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_HISTORIAL" MODIFY ("CREATED_BY" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_HISTORIAL" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_HISTORIAL" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_HISTORIAL" MODIFY ("ID_SOLICITUD" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_HISTORIAL" MODIFY ("USER_TO" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_HISTORIAL" MODIFY ("USER_FROM" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_HISTORIAL" MODIFY ("STATUS_TO" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SOLICITUD_PRODUCTO
--------------------------------------------------------

  ALTER TABLE "SOLICITUD_PRODUCTO" MODIFY ("ID_PRODUCTO" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_PRODUCTO" ADD CONSTRAINT "SOLICITUD_FAMILIA_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SOLICITUD_PRODUCTO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_PRODUCTO" MODIFY ("ID_SOLICITUD" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SOLICITUD_TIPO
--------------------------------------------------------

  ALTER TABLE "SOLICITUD_TIPO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_TIPO" ADD CONSTRAINT "SOLICITUD_TIPO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SOLICITUD_TIPO" ADD CONSTRAINT "SOLICITUD_TIPO_UK" UNIQUE ("CODE") ENABLE;
--------------------------------------------------------
--  Constraints for Table SUB_ESTADO
--------------------------------------------------------

  ALTER TABLE "SUB_ESTADO" ADD CONSTRAINT "ESTADO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SUB_ESTADO" MODIFY ("ID_ESTADO" NOT NULL ENABLE);
  ALTER TABLE "SUB_ESTADO" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TIEMPO_ESTIMADO_FLUJO
--------------------------------------------------------

  ALTER TABLE "TIEMPO_ESTIMADO_FLUJO" ADD CONSTRAINT "TIEMPO_ESTIMADO_FLUJO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "TIEMPO_ESTIMADO_FLUJO" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TIPO_ACTIVIDAD
--------------------------------------------------------

  ALTER TABLE "TIPO_ACTIVIDAD" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "TIPO_ACTIVIDAD" MODIFY ("TIPO_CLIENTE" NOT NULL ENABLE);
  ALTER TABLE "TIPO_ACTIVIDAD" ADD CONSTRAINT "TIPO_ACTIVIDAD_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table TIPO_CLIENTE
--------------------------------------------------------

  ALTER TABLE "TIPO_CLIENTE" ADD CONSTRAINT "TIPO_CLIENTE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "TIPO_CLIENTE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TIPO_COMPROBANTE
--------------------------------------------------------

  ALTER TABLE "TIPO_COMPROBANTE" MODIFY ("MARCA" NOT NULL ENABLE);
  ALTER TABLE "TIPO_COMPROBANTE" MODIFY ("IGV" NOT NULL ENABLE);
  ALTER TABLE "TIPO_COMPROBANTE" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "TIPO_COMPROBANTE" MODIFY ("DESCRIPCION" NOT NULL ENABLE);
  ALTER TABLE "TIPO_COMPROBANTE" MODIFY ("CTA_SUNAT" NOT NULL ENABLE);
  ALTER TABLE "TIPO_COMPROBANTE" ADD CONSTRAINT "TIPO_COMPROBANTE_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table TIPO_GASTO
--------------------------------------------------------

  ALTER TABLE "TIPO_GASTO" ADD CONSTRAINT "PK_TIPOGASTO" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table TIPO_INVERSION
--------------------------------------------------------

  ALTER TABLE "TIPO_INVERSION" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "TIPO_INVERSION" ADD CONSTRAINT "TIPO_INVERSION_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table TIPO_MONEDA
--------------------------------------------------------

  ALTER TABLE "TIPO_MONEDA" ADD CONSTRAINT "PK_TIPO_MONEDA" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table TIPO_PAGO
--------------------------------------------------------

  ALTER TABLE "TIPO_PAGO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "TIPO_PAGO" ADD CONSTRAINT "TIPO_PAGO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table TIPO_REGIMEN
--------------------------------------------------------

  ALTER TABLE "TIPO_REGIMEN" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "TIPO_REGIMEN" ADD CONSTRAINT "RETENCION_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table USER_TEMPORAL
--------------------------------------------------------

  ALTER TABLE "USER_TEMPORAL" ADD CONSTRAINT "USER_TEMPORAL_ID_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "USER_TEMPORAL" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "USER_TEMPORAL" MODIFY ("UPDATED_BY" NOT NULL ENABLE);
  ALTER TABLE "USER_TEMPORAL" MODIFY ("CREATED_BY" NOT NULL ENABLE);
  ALTER TABLE "USER_TEMPORAL" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "USER_TEMPORAL" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "USER_TEMPORAL" MODIFY ("ID_USER_TEMP" NOT NULL ENABLE);
  ALTER TABLE "USER_TEMPORAL" MODIFY ("ID_USER" NOT NULL ENABLE);
--------------------------------------------------------
--  Ref Constraints for Table ASIENTO
--------------------------------------------------------

  ALTER TABLE "ASIENTO" ADD CONSTRAINT "ASIENTO_FK1" FOREIGN KEY ("ID_SOLICITUD")
	  REFERENCES "SOLICITUD" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table DEPOSITO
--------------------------------------------------------

  ALTER TABLE "DEPOSITO" ADD CONSTRAINT "DEPOSITO_FK2" FOREIGN KEY ("NUM_CUENTA")
	  REFERENCES "CUENTA" ("NUM_CUENTA") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table EVENT
--------------------------------------------------------

  ALTER TABLE "EVENT" ADD CONSTRAINT "EVENT_FK1" FOREIGN KEY ("SOLICITUD_ID")
	  REFERENCES "SOLICITUD" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table FILE_STORAGE
--------------------------------------------------------

  ALTER TABLE "FILE_STORAGE" ADD CONSTRAINT "FILE_STORAGE_FK1" FOREIGN KEY ("EVENT_ID")
	  REFERENCES "EVENT" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table FONDO_GERENTE_PRODUCTO
--------------------------------------------------------

  ALTER TABLE "FONDO_GERENTE_PRODUCTO" ADD CONSTRAINT "FONDOS_FK1" FOREIGN KEY ("SUBCATEGORIA_ID")
	  REFERENCES "FONDO_SUBCATEGORIA" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table FONDO_SUBCATEGORIA
--------------------------------------------------------

  ALTER TABLE "FONDO_SUBCATEGORIA" ADD CONSTRAINT "FONDOS_SUBCATEGORIAS_FK2" FOREIGN KEY ("ID_FONDO_CATEGORIA")
	  REFERENCES "FONDO_CATEGORIA" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table FONDO_SUPERVISOR
--------------------------------------------------------

  ALTER TABLE "FONDO_SUPERVISOR" ADD CONSTRAINT "FONDOS_SUPERVISOR_FK1" FOREIGN KEY ("SUBCATEGORIA_ID")
	  REFERENCES "FONDO_SUBCATEGORIA" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table GASTO
--------------------------------------------------------

  ALTER TABLE "GASTO" ADD CONSTRAINT "GASTO_FK1" FOREIGN KEY ("ID_SOLICITUD")
	  REFERENCES "SOLICITUD" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table GASTO_ITEM
--------------------------------------------------------

  ALTER TABLE "GASTO_ITEM" ADD CONSTRAINT "FK_ID_GASTO" FOREIGN KEY ("ID_GASTO")
	  REFERENCES "GASTO" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table INVERSION_ACTIVIDAD
--------------------------------------------------------

  ALTER TABLE "INVERSION_ACTIVIDAD" ADD CONSTRAINT "INVERSION_ACTIVIDAD_FK1" FOREIGN KEY ("ID_INVERSION")
	  REFERENCES "TIPO_INVERSION" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "INVERSION_ACTIVIDAD" ADD CONSTRAINT "INVERSION_ACTIVIDAD_FK2" FOREIGN KEY ("ID_ACTIVIDAD")
	  REFERENCES "TIPO_ACTIVIDAD" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table INVERSION_POLITICA_APROBACION
--------------------------------------------------------

  ALTER TABLE "INVERSION_POLITICA_APROBACION" ADD CONSTRAINT "INVERSION_POLITICA_APROBA_FK1" FOREIGN KEY ("ID_INVERSION")
	  REFERENCES "TIPO_INVERSION" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "INVERSION_POLITICA_APROBACION" ADD CONSTRAINT "INVERSION_POLITICA_APROBA_FK2" FOREIGN KEY ("ID_POLITICA_APROBACION")
	  REFERENCES "POLITICA_APROBACION" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table REPORTE_FORMULA
--------------------------------------------------------

  ALTER TABLE "REPORTE_FORMULA" ADD CONSTRAINT "REPORTE_FORMULA_FK1" FOREIGN KEY ("QUERY_ID")
	  REFERENCES "REPORTE_QUERY" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table REPORTE_USUARIO
--------------------------------------------------------

  ALTER TABLE "REPORTE_USUARIO" ADD CONSTRAINT "REPORTE_USUARIO_FK1" FOREIGN KEY ("ID_REPORTE")
	  REFERENCES "REPORTE_FORMULA" ("ID_REPORTE") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table SOLICITUD
--------------------------------------------------------

  ALTER TABLE "SOLICITUD" ADD CONSTRAINT "SOLICITUD_FK1" FOREIGN KEY ("ID_ESTADO")
	  REFERENCES "SUB_ESTADO" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SOLICITUD" ADD CONSTRAINT "SOLICITUD_FK2" FOREIGN KEY ("ID_ACTIVIDAD")
	  REFERENCES "TIPO_ACTIVIDAD" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SOLICITUD" ADD CONSTRAINT "SOLICITUD_FK3" FOREIGN KEY ("ID_INVERSION")
	  REFERENCES "TIPO_INVERSION" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SOLICITUD" ADD CONSTRAINT "SOLICITUD_FK4" FOREIGN KEY ("IDTIPOSOLICITUD")
	  REFERENCES "SOLICITUD_TIPO" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table SOLICITUD_CLIENTE
--------------------------------------------------------

  ALTER TABLE "SOLICITUD_CLIENTE" ADD CONSTRAINT "SOLICITUD_CLIENTE_FK1" FOREIGN KEY ("ID_SOLICITUD")
	  REFERENCES "SOLICITUD" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SOLICITUD_CLIENTE" ADD CONSTRAINT "SOLICITUD_CLIENTE_FK2" FOREIGN KEY ("ID_TIPO_CLIENTE")
	  REFERENCES "TIPO_CLIENTE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table SOLICITUD_DETALLE
--------------------------------------------------------

  ALTER TABLE "SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_FK1" FOREIGN KEY ("ID")
	  REFERENCES "SOLICITUD" ("ID_DETALLE") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_FK2" FOREIGN KEY ("ID_MONEDA")
	  REFERENCES "TIPO_MONEDA" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_FK3" FOREIGN KEY ("ID_PAGO")
	  REFERENCES "TIPO_PAGO" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_FK4" FOREIGN KEY ("ID_FONDO")
	  REFERENCES "FONDO_CONTABLE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_FK5" FOREIGN KEY ("ID_MOTIVO")
	  REFERENCES "MOTIVO" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_FK6" FOREIGN KEY ("ID_PERIODO")
	  REFERENCES "PERIODO" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_FK7" FOREIGN KEY ("ID_DEPOSITO")
	  REFERENCES "DEPOSITO" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table SOLICITUD_GERENTE
--------------------------------------------------------

  ALTER TABLE "SOLICITUD_GERENTE" ADD CONSTRAINT "SOLICITUD_GERENTE_FK1" FOREIGN KEY ("ID_SOLICITUD")
	  REFERENCES "SOLICITUD" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table SOLICITUD_HISTORIAL
--------------------------------------------------------

  ALTER TABLE "SOLICITUD_HISTORIAL" ADD CONSTRAINT "SOLICITUD_HISTORIAL_FK1" FOREIGN KEY ("ID_SOLICITUD")
	  REFERENCES "SOLICITUD" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table SOLICITUD_PRODUCTO
--------------------------------------------------------

  ALTER TABLE "SOLICITUD_PRODUCTO" ADD CONSTRAINT "SOLICITUD_PRODUCTO_FK1" FOREIGN KEY ("ID_SOLICITUD")
	  REFERENCES "SOLICITUD" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table SUB_ESTADO
--------------------------------------------------------

  ALTER TABLE "SUB_ESTADO" ADD CONSTRAINT "SUB_ESTADO_FK1" FOREIGN KEY ("ID_ESTADO")
	  REFERENCES "ESTADO" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TIPO_ACTIVIDAD
--------------------------------------------------------

  ALTER TABLE "TIPO_ACTIVIDAD" ADD CONSTRAINT "TIPO_ACTIVIDAD_FK1" FOREIGN KEY ("TIPO_CLIENTE")
	  REFERENCES "TIPO_CLIENTE" ("ID") ON DELETE CASCADE ENABLE;
