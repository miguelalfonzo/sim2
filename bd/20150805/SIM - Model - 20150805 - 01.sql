--------------------------------------------------------
-- Archivo creado  - miércoles-agosto-05-2015   
--------------------------------------------------------
--------------------------------------------------------
--  DDL for Table ASIENTO
--------------------------------------------------------

  CREATE TABLE "SIM"."ASIENTO" 
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

  CREATE TABLE "SIM"."CUENTA" 
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

  CREATE TABLE "SIM"."CUENTA_GASTO_MARCA" 
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

  CREATE TABLE "SIM"."CUENTA_TIPO" 
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

  CREATE TABLE "SIM"."DEPOSITO" 
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

  CREATE TABLE "SIM"."DOCUMENTO" 
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

  CREATE TABLE "SIM"."ESTADO" 
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

  CREATE TABLE "SIM"."EVENT" 
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

  CREATE TABLE "SIM"."FILE_STORAGE" 
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

  CREATE TABLE "SIM"."FONDO_CATEGORIA" 
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

  CREATE TABLE "SIM"."FONDO_CONTABLE" 
   (	"ID" NUMBER, 
	"NOMBRE" VARCHAR2(100), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_AT" DATE, 
	"DELETED_BY" NUMBER, 
	"IDTIPOFONDO" NUMBER DEFAULT 1, 
	"NUM_CUENTA" NUMBER, 
	"ID_MONEDA" NUMBER DEFAULT 1
   ) ;
--------------------------------------------------------
--  DDL for Table FONDO_CONTABLE_HISTORIA
--------------------------------------------------------

  CREATE TABLE "SIM"."FONDO_CONTABLE_HISTORIA" 
   (	"ID" NUMBER, 
	"SALDO_INICIAL" FLOAT(63), 
	"SALDO_FINAL" FLOAT(63), 
	"ID_SOLICITUD" NUMBER, 
	"ID_FONDO" NUMBER, 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table FONDO_GERENTE_PRODUCTO
--------------------------------------------------------

  CREATE TABLE "SIM"."FONDO_GERENTE_PRODUCTO" 
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

  CREATE TABLE "SIM"."FONDO_INSTITUCION" 
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

  CREATE TABLE "SIM"."FONDO_MARKETING_HISTORIA" 
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

  CREATE TABLE "SIM"."FONDO_SUBCATEGORIA" 
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
	"TIPO" CHAR(1), 
	"ID_FONDO" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table FONDO_SUPERVISOR
--------------------------------------------------------

  CREATE TABLE "SIM"."FONDO_SUPERVISOR" 
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

  CREATE TABLE "SIM"."FONDO_TIPO" 
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

  CREATE TABLE "SIM"."GASTO" 
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

  CREATE TABLE "SIM"."GASTO_ITEM" 
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

  CREATE TABLE "SIM"."INVERSION_ACTIVIDAD" 
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

  CREATE TABLE "SIM"."INVERSION_POLITICA_APROBACION" 
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

  CREATE TABLE "SIM"."MANTENIMIENTO" 
   (	"ID" NUMBER, 
	"DESCRIPCION" VARCHAR2(60), 
	"FORMULA" NVARCHAR2(1000)
   ) ;
--------------------------------------------------------
--  DDL for Table MARCA
--------------------------------------------------------

  CREATE TABLE "SIM"."MARCA" 
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

  CREATE TABLE "SIM"."MARCA_TIPO" 
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

  CREATE TABLE "SIM"."MOTIVO" 
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

  CREATE TABLE "SIM"."PARAMETRO" 
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

  CREATE TABLE "SIM"."PERIODO" 
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

  CREATE TABLE "SIM"."POLITICA_APROBACION" 
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

  CREATE TABLE "SIM"."REPORTE_FORMULA" 
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

  CREATE TABLE "SIM"."REPORTE_QUERY" 
   (	"ID" NUMBER, 
	"NAME" VARCHAR2(50), 
	"QUERY" CLOB
   ) ;
--------------------------------------------------------
--  DDL for Table REPORTE_USUARIO
--------------------------------------------------------

  CREATE TABLE "SIM"."REPORTE_USUARIO" 
   (	"ID" NUMBER(*,0), 
	"ID_REPORTE" NUMBER(*,0), 
	"ID_USUARIO" NUMBER(*,0), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE
   ) ;
--------------------------------------------------------
--  DDL for Table SOLICITUD
--------------------------------------------------------

  CREATE TABLE "SIM"."SOLICITUD" 
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

  CREATE TABLE "SIM"."SOLICITUD_CLIENTE" 
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

  CREATE TABLE "SIM"."SOLICITUD_DETALLE" 
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

  CREATE TABLE "SIM"."SOLICITUD_GERENTE" 
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

  CREATE TABLE "SIM"."SOLICITUD_HISTORIAL" 
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

  CREATE TABLE "SIM"."SOLICITUD_PRODUCTO" 
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

  CREATE TABLE "SIM"."SOLICITUD_TIPO" 
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

  CREATE TABLE "SIM"."SUB_ESTADO" 
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
--  DDL for Table TIPO_ACTIVIDAD
--------------------------------------------------------

  CREATE TABLE "SIM"."TIPO_ACTIVIDAD" 
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

  CREATE TABLE "SIM"."TIPO_CLIENTE" 
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

  CREATE TABLE "SIM"."TIPO_COMPROBANTE" 
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

  CREATE TABLE "SIM"."TIPO_GASTO" 
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

  CREATE TABLE "SIM"."TIPO_INVERSION" 
   (	"ID" NUMBER, 
	"NOMBRE" VARCHAR2(45), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"CODIGO_ACTIVIDAD" CHAR(1 CHAR)
   ) ;
--------------------------------------------------------
--  DDL for Table TIPO_MONEDA
--------------------------------------------------------

  CREATE TABLE "SIM"."TIPO_MONEDA" 
   (	"ID" NUMBER(1,0), 
	"DESCRIPCION" VARCHAR2(10), 
	"SIMBOLO" VARCHAR2(4), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE
   ) ;
--------------------------------------------------------
--  DDL for Table TIPO_PAGO
--------------------------------------------------------

  CREATE TABLE "SIM"."TIPO_PAGO" 
   (	"ID" NUMBER(10,0), 
	"NOMBRE" VARCHAR2(30), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE
   ) ;
--------------------------------------------------------
--  DDL for Table TIPO_REGIMEN
--------------------------------------------------------

  CREATE TABLE "SIM"."TIPO_REGIMEN" 
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

  CREATE TABLE "SIM"."USER_TEMPORAL" 
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

  CREATE UNIQUE INDEX "SIM"."ASIENTO_PK" ON "SIM"."ASIENTO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index CUENTA_GASTO_MARC_UK2
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."CUENTA_GASTO_MARC_UK2" ON "SIM"."CUENTA_GASTO_MARCA" ("NUM_CUENTA_FONDO", "NUM_CUENTA_GASTO", "MARCA_CODIGO", "IDDOCUMENTO") 
  ;
--------------------------------------------------------
--  DDL for Index CUENTA_MARCA_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."CUENTA_MARCA_PK" ON "SIM"."CUENTA_GASTO_MARCA" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index CUENTA_TIPO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."CUENTA_TIPO_PK" ON "SIM"."CUENTA_TIPO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index DEPOSITO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."DEPOSITO_PK" ON "SIM"."DEPOSITO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index DMKT2_RG_CUENTA_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."DMKT2_RG_CUENTA_PK" ON "SIM"."CUENTA" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index DMKT2_RG_CUENTA_UK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."DMKT2_RG_CUENTA_UK" ON "SIM"."CUENTA" ("NUM_CUENTA") 
  ;
--------------------------------------------------------
--  DDL for Index DOCUMENTO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."DOCUMENTO_PK" ON "SIM"."DOCUMENTO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index DOCUMENTO_UK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."DOCUMENTO_UK" ON "SIM"."DOCUMENTO" ("CODIGO") 
  ;
--------------------------------------------------------
--  DDL for Index ESTADO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."ESTADO_PK" ON "SIM"."SUB_ESTADO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index ESTADO_RANGO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."ESTADO_RANGO_PK" ON "SIM"."ESTADO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index EVENT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."EVENT_PK" ON "SIM"."EVENT" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FILE_STORAGE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."FILE_STORAGE_PK" ON "SIM"."FILE_STORAGE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDO_HISTORIA_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."FONDO_HISTORIA_PK" ON "SIM"."FONDO_CONTABLE_HISTORIA" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDO_INSTITUCION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."FONDO_INSTITUCION_PK" ON "SIM"."FONDO_INSTITUCION" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDO_INSTITUCION_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."FONDO_INSTITUCION_UK1" ON "SIM"."FONDO_INSTITUCION" ("SUBCATEGORIA_ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDO_MARKETING_HISTORIA_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."FONDO_MARKETING_HISTORIA_PK" ON "SIM"."FONDO_MARKETING_HISTORIA" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."FONDO_PK" ON "SIM"."FONDO_CONTABLE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDOS_CATEGORIAS_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."FONDOS_CATEGORIAS_PK" ON "SIM"."FONDO_CATEGORIA" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDOS_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."FONDOS_PK" ON "SIM"."FONDO_GERENTE_PRODUCTO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDOS_SUBCATEGORIAS_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."FONDOS_SUBCATEGORIAS_PK" ON "SIM"."FONDO_SUBCATEGORIA" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDOS_SUPERVISOR_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."FONDOS_SUPERVISOR_PK" ON "SIM"."FONDO_SUPERVISOR" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDOS_SUPERVISOR_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."FONDOS_SUPERVISOR_UK1" ON "SIM"."FONDO_SUPERVISOR" ("SUPERVISOR_ID", "MARCA_ID", "SUBCATEGORIA_ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDOS_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."FONDOS_UK1" ON "SIM"."FONDO_GERENTE_PRODUCTO" ("SUBCATEGORIA_ID", "MARCA_ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDO_TIPO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."FONDO_TIPO_PK" ON "SIM"."FONDO_TIPO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDO_UK2
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."FONDO_UK2" ON "SIM"."FONDO_CONTABLE" ("NUM_CUENTA") 
  ;
--------------------------------------------------------
--  DDL for Index GASTO_ITEM_PK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."GASTO_ITEM_PK1" ON "SIM"."GASTO_ITEM" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index GASTO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."GASTO_PK" ON "SIM"."GASTO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index ID_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."ID_PK" ON "SIM"."TIPO_ACTIVIDAD" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index INVERSION_ACTIVIDAD_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."INVERSION_ACTIVIDAD_PK" ON "SIM"."INVERSION_ACTIVIDAD" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index INVERSION_ACTIVIDAD_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."INVERSION_ACTIVIDAD_UK1" ON "SIM"."INVERSION_ACTIVIDAD" ("ID_INVERSION", "ID_ACTIVIDAD") 
  ;
--------------------------------------------------------
--  DDL for Index INVERSION_POLITICA_APROBAC_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."INVERSION_POLITICA_APROBAC_PK" ON "SIM"."INVERSION_POLITICA_APROBACION" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index INVERSION_POLITICA_APROBA_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."INVERSION_POLITICA_APROBA_UK1" ON "SIM"."INVERSION_POLITICA_APROBACION" ("ID_INVERSION", "ID_POLITICA_APROBACION") 
  ;
--------------------------------------------------------
--  DDL for Index MANTENIMIENTO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."MANTENIMIENTO_PK" ON "SIM"."MANTENIMIENTO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index MARCA_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."MARCA_PK" ON "SIM"."MARCA" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index MARCA_TIPO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."MARCA_TIPO_PK" ON "SIM"."MARCA_TIPO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index PARAMETRO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."PARAMETRO_PK" ON "SIM"."PARAMETRO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index PERIODO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."PERIODO_PK" ON "SIM"."PERIODO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index PK_TIPOGASTO
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."PK_TIPOGASTO" ON "SIM"."TIPO_GASTO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index PK_TIPO_MONEDA
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."PK_TIPO_MONEDA" ON "SIM"."TIPO_MONEDA" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index POLITICA_APROBACION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."POLITICA_APROBACION_PK" ON "SIM"."POLITICA_APROBACION" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index POLITICA_APROBACION_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."POLITICA_APROBACION_UK1" ON "SIM"."POLITICA_APROBACION" ("DESDE", "HASTA", "ORDEN", "TIPO_USUARIO") 
  ;
--------------------------------------------------------
--  DDL for Index REPORTE_FORMULA_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."REPORTE_FORMULA_PK" ON "SIM"."REPORTE_FORMULA" ("ID_REPORTE") 
  ;
--------------------------------------------------------
--  DDL for Index REPORTE_QUERY_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."REPORTE_QUERY_PK" ON "SIM"."REPORTE_QUERY" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index REPORTE_USUARIO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."REPORTE_USUARIO_PK" ON "SIM"."REPORTE_USUARIO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index RETENCION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."RETENCION_PK" ON "SIM"."TIPO_REGIMEN" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_CLIENTE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."SOLICITUD_CLIENTE_PK" ON "SIM"."SOLICITUD_CLIENTE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_CLIENTE_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."SOLICITUD_CLIENTE_UK1" ON "SIM"."SOLICITUD_CLIENTE" ("ID_SOLICITUD", "ID_CLIENTE", "ID_TIPO_CLIENTE") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_DERIVADO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."SOLICITUD_DERIVADO_PK" ON "SIM"."SOLICITUD_GERENTE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_DETALLE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."SOLICITUD_DETALLE_PK" ON "SIM"."SOLICITUD_DETALLE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_DETALLE_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."SOLICITUD_DETALLE_UK1" ON "SIM"."SOLICITUD_DETALLE" ("ID_DEPOSITO") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_FAMILIA_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."SOLICITUD_FAMILIA_PK" ON "SIM"."SOLICITUD_PRODUCTO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_HISTORIAL_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."SOLICITUD_HISTORIAL_PK" ON "SIM"."SOLICITUD_HISTORIAL" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."SOLICITUD_PK" ON "SIM"."SOLICITUD" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_TIPO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."SOLICITUD_TIPO_PK" ON "SIM"."SOLICITUD_TIPO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_TIPO_UK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."SOLICITUD_TIPO_UK" ON "SIM"."SOLICITUD_TIPO" ("CODE") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."SOLICITUD_UK1" ON "SIM"."SOLICITUD" ("TOKEN") 
  ;
--------------------------------------------------------
--  DDL for Index SOLICITUD_UK2
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."SOLICITUD_UK2" ON "SIM"."SOLICITUD" ("ID_DETALLE") 
  ;
--------------------------------------------------------
--  DDL for Index TIPO_CLIENTE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."TIPO_CLIENTE_PK" ON "SIM"."TIPO_CLIENTE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TIPO_COMPROBANTE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."TIPO_COMPROBANTE_PK" ON "SIM"."TIPO_COMPROBANTE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TIPO_INVERSION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."TIPO_INVERSION_PK" ON "SIM"."TIPO_INVERSION" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TIPO_PAGO_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."TIPO_PAGO_PK" ON "SIM"."TIPO_PAGO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TIPO_SOLICITUD_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."TIPO_SOLICITUD_PK" ON "SIM"."MOTIVO" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index USER_TEMPORAL_ID_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "SIM"."USER_TEMPORAL_ID_PK" ON "SIM"."USER_TEMPORAL" ("ID") 
  ;
--------------------------------------------------------
--  Constraints for Table ASIENTO
--------------------------------------------------------

  ALTER TABLE "SIM"."ASIENTO" MODIFY ("TIPO_ASIENTO" NOT NULL ENABLE);
  ALTER TABLE "SIM"."ASIENTO" ADD CONSTRAINT "ASIENTO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."ASIENTO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."ASIENTO" MODIFY ("UPDATED_BY" NOT NULL ENABLE);
  ALTER TABLE "SIM"."ASIENTO" MODIFY ("CREATED_BY" NOT NULL ENABLE);
  ALTER TABLE "SIM"."ASIENTO" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "SIM"."ASIENTO" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "SIM"."ASIENTO" MODIFY ("IMPORTE" NOT NULL ENABLE);
  ALTER TABLE "SIM"."ASIENTO" MODIFY ("D_C" NOT NULL ENABLE);
  ALTER TABLE "SIM"."ASIENTO" MODIFY ("FEC_ORIGEN" NOT NULL ENABLE);
  ALTER TABLE "SIM"."ASIENTO" MODIFY ("NUM_CUENTA" NOT NULL ENABLE);
  ALTER TABLE "SIM"."ASIENTO" MODIFY ("ID_SOLICITUD" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table CUENTA
--------------------------------------------------------

  ALTER TABLE "SIM"."CUENTA" ADD CONSTRAINT "DMKT2_RG_CUENTA_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."CUENTA" ADD CONSTRAINT "DMKT2_RG_CUENTA_UK" UNIQUE ("NUM_CUENTA") ENABLE;
  ALTER TABLE "SIM"."CUENTA" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."CUENTA" MODIFY ("NUM_CUENTA" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table CUENTA_GASTO_MARCA
--------------------------------------------------------

  ALTER TABLE "SIM"."CUENTA_GASTO_MARCA" ADD CONSTRAINT "CUENTA_GASTO_MARC_UK2" UNIQUE ("NUM_CUENTA_FONDO", "NUM_CUENTA_GASTO", "MARCA_CODIGO", "IDDOCUMENTO") ENABLE;
  ALTER TABLE "SIM"."CUENTA_GASTO_MARCA" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."CUENTA_GASTO_MARCA" ADD CONSTRAINT "CUENTA_MARCA_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table CUENTA_TIPO
--------------------------------------------------------

  ALTER TABLE "SIM"."CUENTA_TIPO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."CUENTA_TIPO" ADD CONSTRAINT "CUENTA_TIPO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table DEPOSITO
--------------------------------------------------------

  ALTER TABLE "SIM"."DEPOSITO" MODIFY ("NUM_CUENTA" NOT NULL ENABLE);
  ALTER TABLE "SIM"."DEPOSITO" MODIFY ("NUM_TRANSFERENCIA" NOT NULL ENABLE);
  ALTER TABLE "SIM"."DEPOSITO" ADD CONSTRAINT "PK_CODDEPOSITO" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."DEPOSITO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."DEPOSITO" MODIFY ("TOTAL" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table DOCUMENTO
--------------------------------------------------------

  ALTER TABLE "SIM"."DOCUMENTO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."DOCUMENTO" MODIFY ("CODIGO" NOT NULL ENABLE);
  ALTER TABLE "SIM"."DOCUMENTO" ADD CONSTRAINT "DOCUMENTO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."DOCUMENTO" ADD CONSTRAINT "DOCUMENTO_UK" UNIQUE ("CODIGO") ENABLE;
--------------------------------------------------------
--  Constraints for Table ESTADO
--------------------------------------------------------

  ALTER TABLE "SIM"."ESTADO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."ESTADO" ADD CONSTRAINT "ESTADO_RANGO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table EVENT
--------------------------------------------------------

  ALTER TABLE "SIM"."EVENT" MODIFY ("EVENT_DATE" NOT NULL ENABLE);
  ALTER TABLE "SIM"."EVENT" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."EVENT" MODIFY ("NAME" NOT NULL ENABLE);
  ALTER TABLE "SIM"."EVENT" MODIFY ("DESCRIPTION" NOT NULL ENABLE);
  ALTER TABLE "SIM"."EVENT" ADD CONSTRAINT "EVENT_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table FILE_STORAGE
--------------------------------------------------------

  ALTER TABLE "SIM"."FILE_STORAGE" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."FILE_STORAGE" MODIFY ("NAME" NOT NULL ENABLE);
  ALTER TABLE "SIM"."FILE_STORAGE" MODIFY ("EXTENSION" NOT NULL ENABLE);
  ALTER TABLE "SIM"."FILE_STORAGE" MODIFY ("DIRECTORY" NOT NULL ENABLE);
  ALTER TABLE "SIM"."FILE_STORAGE" MODIFY ("APP" NOT NULL ENABLE);
  ALTER TABLE "SIM"."FILE_STORAGE" ADD CONSTRAINT "FILE_STORAGE_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table FONDO_CATEGORIA
--------------------------------------------------------

  ALTER TABLE "SIM"."FONDO_CATEGORIA" ADD CONSTRAINT "FONDOS_CATEGORIAS_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."FONDO_CATEGORIA" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FONDO_CONTABLE
--------------------------------------------------------

  ALTER TABLE "SIM"."FONDO_CONTABLE" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."FONDO_CONTABLE" ADD CONSTRAINT "FONDO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."FONDO_CONTABLE" ADD CONSTRAINT "FONDO_UK2" UNIQUE ("NUM_CUENTA") ENABLE;
--------------------------------------------------------
--  Constraints for Table FONDO_CONTABLE_HISTORIA
--------------------------------------------------------

  ALTER TABLE "SIM"."FONDO_CONTABLE_HISTORIA" ADD CONSTRAINT "FONDO_HISTORIA_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."FONDO_CONTABLE_HISTORIA" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FONDO_GERENTE_PRODUCTO
--------------------------------------------------------

  ALTER TABLE "SIM"."FONDO_GERENTE_PRODUCTO" ADD CONSTRAINT "FONDOS_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."FONDO_GERENTE_PRODUCTO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."FONDO_GERENTE_PRODUCTO" MODIFY ("MARCA_ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."FONDO_GERENTE_PRODUCTO" MODIFY ("SUBCATEGORIA_ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."FONDO_GERENTE_PRODUCTO" ADD CONSTRAINT "FONDOS_UK1" UNIQUE ("SUBCATEGORIA_ID", "MARCA_ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table FONDO_INSTITUCION
--------------------------------------------------------

  ALTER TABLE "SIM"."FONDO_INSTITUCION" ADD CONSTRAINT "FONDO_INSTITUCION_UK1" UNIQUE ("SUBCATEGORIA_ID") ENABLE;
  ALTER TABLE "SIM"."FONDO_INSTITUCION" ADD CONSTRAINT "FONDO_INSTITUCION_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."FONDO_INSTITUCION" MODIFY ("SUBCATEGORIA_ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."FONDO_INSTITUCION" MODIFY ("SALDO" NOT NULL ENABLE);
  ALTER TABLE "SIM"."FONDO_INSTITUCION" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FONDO_MARKETING_HISTORIA
--------------------------------------------------------

  ALTER TABLE "SIM"."FONDO_MARKETING_HISTORIA" MODIFY ("ID_FONDO_HISTORY_REASON" NOT NULL ENABLE);
  ALTER TABLE "SIM"."FONDO_MARKETING_HISTORIA" ADD CONSTRAINT "FONDO_MARKETING_HISTORIA_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."FONDO_MARKETING_HISTORIA" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FONDO_SUBCATEGORIA
--------------------------------------------------------

  ALTER TABLE "SIM"."FONDO_SUBCATEGORIA" ADD CONSTRAINT "FONDOS_SUBCATEGORIAS_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."FONDO_SUBCATEGORIA" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."FONDO_SUBCATEGORIA" MODIFY ("ID_FONDO" NOT NULL ENABLE);
  ALTER TABLE "SIM"."FONDO_SUBCATEGORIA" MODIFY ("ID_FONDO_CATEGORIA" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FONDO_SUPERVISOR
--------------------------------------------------------

  ALTER TABLE "SIM"."FONDO_SUPERVISOR" ADD CONSTRAINT "FONDOS_SUPERVISOR_UK1" UNIQUE ("SUPERVISOR_ID", "MARCA_ID", "SUBCATEGORIA_ID") ENABLE;
  ALTER TABLE "SIM"."FONDO_SUPERVISOR" ADD CONSTRAINT "FONDOS_SUPERVISOR_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."FONDO_SUPERVISOR" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."FONDO_SUPERVISOR" MODIFY ("MARCA_ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."FONDO_SUPERVISOR" MODIFY ("SUBCATEGORIA_ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."FONDO_SUPERVISOR" MODIFY ("SUPERVISOR_ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FONDO_TIPO
--------------------------------------------------------

  ALTER TABLE "SIM"."FONDO_TIPO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."FONDO_TIPO" ADD CONSTRAINT "FONDO_TIPO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table GASTO
--------------------------------------------------------

  ALTER TABLE "SIM"."GASTO" ADD CONSTRAINT "PK_ID_GASTO" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."GASTO" MODIFY ("ID_SOLICITUD" NOT NULL ENABLE);
  ALTER TABLE "SIM"."GASTO" MODIFY ("IDCOMPROBANTE" NOT NULL ENABLE);
  ALTER TABLE "SIM"."GASTO" ADD CONSTRAINT "GASTO_CHK2" CHECK (NUM_SERIE >= 1) ENABLE;
--------------------------------------------------------
--  Constraints for Table GASTO_ITEM
--------------------------------------------------------

  ALTER TABLE "SIM"."GASTO_ITEM" MODIFY ("ID_GASTO" NOT NULL ENABLE);
  ALTER TABLE "SIM"."GASTO_ITEM" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."GASTO_ITEM" ADD CONSTRAINT "GASTO_ITEM_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."GASTO_ITEM" MODIFY ("IMPORTE" NOT NULL ENABLE);
  ALTER TABLE "SIM"."GASTO_ITEM" MODIFY ("TIPO_GASTO" NOT NULL ENABLE);
  ALTER TABLE "SIM"."GASTO_ITEM" MODIFY ("CANTIDAD" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table INVERSION_ACTIVIDAD
--------------------------------------------------------

  ALTER TABLE "SIM"."INVERSION_ACTIVIDAD" ADD CONSTRAINT "INVERSION_ACTIVIDAD_UK1" UNIQUE ("ID_INVERSION", "ID_ACTIVIDAD") ENABLE;
  ALTER TABLE "SIM"."INVERSION_ACTIVIDAD" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."INVERSION_ACTIVIDAD" MODIFY ("ID_ACTIVIDAD" NOT NULL ENABLE);
  ALTER TABLE "SIM"."INVERSION_ACTIVIDAD" MODIFY ("ID_INVERSION" NOT NULL ENABLE);
  ALTER TABLE "SIM"."INVERSION_ACTIVIDAD" ADD CONSTRAINT "INVERSION_ACTIVIDAD_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table INVERSION_POLITICA_APROBACION
--------------------------------------------------------

  ALTER TABLE "SIM"."INVERSION_POLITICA_APROBACION" ADD CONSTRAINT "INVERSION_POLITICA_APROBA_UK1" UNIQUE ("ID_INVERSION", "ID_POLITICA_APROBACION") ENABLE;
  ALTER TABLE "SIM"."INVERSION_POLITICA_APROBACION" ADD CONSTRAINT "INVERSION_POLITICA_APROBAC_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."INVERSION_POLITICA_APROBACION" MODIFY ("ID_POLITICA_APROBACION" NOT NULL ENABLE);
  ALTER TABLE "SIM"."INVERSION_POLITICA_APROBACION" MODIFY ("ID_INVERSION" NOT NULL ENABLE);
  ALTER TABLE "SIM"."INVERSION_POLITICA_APROBACION" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table MANTENIMIENTO
--------------------------------------------------------

  ALTER TABLE "SIM"."MANTENIMIENTO" ADD CONSTRAINT "MANTENIMIENTO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."MANTENIMIENTO" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table MARCA
--------------------------------------------------------

  ALTER TABLE "SIM"."MARCA" MODIFY ("CODIGO" NOT NULL ENABLE);
  ALTER TABLE "SIM"."MARCA" ADD CONSTRAINT "MARCA_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."MARCA" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table MARCA_TIPO
--------------------------------------------------------

  ALTER TABLE "SIM"."MARCA_TIPO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."MARCA_TIPO" ADD CONSTRAINT "MARCA_TIPO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table MOTIVO
--------------------------------------------------------

  ALTER TABLE "SIM"."MOTIVO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."MOTIVO" ADD CONSTRAINT "TIPO_SOLICITUD_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table PARAMETRO
--------------------------------------------------------

  ALTER TABLE "SIM"."PARAMETRO" ADD CONSTRAINT "PARAMETRO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."PARAMETRO" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table PERIODO
--------------------------------------------------------

  ALTER TABLE "SIM"."PERIODO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."PERIODO" ADD CONSTRAINT "PERIODO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table POLITICA_APROBACION
--------------------------------------------------------

  ALTER TABLE "SIM"."POLITICA_APROBACION" ADD CONSTRAINT "POLITICA_APROBACION_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."POLITICA_APROBACION" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."POLITICA_APROBACION" ADD CONSTRAINT "POLITICA_APROBACION_UK1" UNIQUE ("DESDE", "HASTA", "ORDEN", "TIPO_USUARIO") ENABLE;
  ALTER TABLE "SIM"."POLITICA_APROBACION" MODIFY ("TIPO_USUARIO" NOT NULL ENABLE);
  ALTER TABLE "SIM"."POLITICA_APROBACION" MODIFY ("ORDEN" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table REPORTE_FORMULA
--------------------------------------------------------

  ALTER TABLE "SIM"."REPORTE_FORMULA" MODIFY ("ID_REPORTE" NOT NULL ENABLE);
  ALTER TABLE "SIM"."REPORTE_FORMULA" ADD CONSTRAINT "REPORTE_FORMULA_PK" PRIMARY KEY ("ID_REPORTE") ENABLE;
--------------------------------------------------------
--  Constraints for Table REPORTE_QUERY
--------------------------------------------------------

  ALTER TABLE "SIM"."REPORTE_QUERY" ADD CONSTRAINT "REPORTE_QUERY_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."REPORTE_QUERY" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table REPORTE_USUARIO
--------------------------------------------------------

  ALTER TABLE "SIM"."REPORTE_USUARIO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."REPORTE_USUARIO" ADD CONSTRAINT "REPORTE_USUARIO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table SOLICITUD
--------------------------------------------------------

  ALTER TABLE "SIM"."SOLICITUD" ADD CONSTRAINT "SOLICITUD_UK1" UNIQUE ("TOKEN") ENABLE;
  ALTER TABLE "SIM"."SOLICITUD" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."SOLICITUD" ADD CONSTRAINT "SOLICITUD_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."SOLICITUD" ADD CONSTRAINT "SOLICITUD_UK2" UNIQUE ("ID_DETALLE") ENABLE;
  ALTER TABLE "SIM"."SOLICITUD" MODIFY ("ID_DETALLE" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SOLICITUD_CLIENTE
--------------------------------------------------------

  ALTER TABLE "SIM"."SOLICITUD_CLIENTE" ADD CONSTRAINT "SOLICITUD_CLIENTE_UK1" UNIQUE ("ID_SOLICITUD", "ID_CLIENTE", "ID_TIPO_CLIENTE") ENABLE;
  ALTER TABLE "SIM"."SOLICITUD_CLIENTE" MODIFY ("ID_TIPO_CLIENTE" NOT NULL ENABLE);
  ALTER TABLE "SIM"."SOLICITUD_CLIENTE" MODIFY ("ID_CLIENTE" NOT NULL ENABLE);
  ALTER TABLE "SIM"."SOLICITUD_CLIENTE" ADD CONSTRAINT "SOLICITUD_CLIENTE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."SOLICITUD_CLIENTE" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."SOLICITUD_CLIENTE" MODIFY ("ID_SOLICITUD" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SOLICITUD_DETALLE
--------------------------------------------------------

  ALTER TABLE "SIM"."SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_UK1" UNIQUE ("ID_DEPOSITO") ENABLE;
  ALTER TABLE "SIM"."SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."SOLICITUD_DETALLE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SOLICITUD_GERENTE
--------------------------------------------------------

  ALTER TABLE "SIM"."SOLICITUD_GERENTE" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."SOLICITUD_GERENTE" ADD CONSTRAINT "SOLICITUD_DERIVADO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table SOLICITUD_HISTORIAL
--------------------------------------------------------

  ALTER TABLE "SIM"."SOLICITUD_HISTORIAL" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."SOLICITUD_HISTORIAL" ADD CONSTRAINT "SOLICITUD_HISTORIAL_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."SOLICITUD_HISTORIAL" MODIFY ("UPDATED_BY" NOT NULL ENABLE);
  ALTER TABLE "SIM"."SOLICITUD_HISTORIAL" MODIFY ("CREATED_BY" NOT NULL ENABLE);
  ALTER TABLE "SIM"."SOLICITUD_HISTORIAL" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "SIM"."SOLICITUD_HISTORIAL" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "SIM"."SOLICITUD_HISTORIAL" MODIFY ("ID_SOLICITUD" NOT NULL ENABLE);
  ALTER TABLE "SIM"."SOLICITUD_HISTORIAL" MODIFY ("USER_TO" NOT NULL ENABLE);
  ALTER TABLE "SIM"."SOLICITUD_HISTORIAL" MODIFY ("USER_FROM" NOT NULL ENABLE);
  ALTER TABLE "SIM"."SOLICITUD_HISTORIAL" MODIFY ("STATUS_TO" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SOLICITUD_PRODUCTO
--------------------------------------------------------

  ALTER TABLE "SIM"."SOLICITUD_PRODUCTO" MODIFY ("ID_PRODUCTO" NOT NULL ENABLE);
  ALTER TABLE "SIM"."SOLICITUD_PRODUCTO" ADD CONSTRAINT "SOLICITUD_FAMILIA_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."SOLICITUD_PRODUCTO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."SOLICITUD_PRODUCTO" MODIFY ("ID_SOLICITUD" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SOLICITUD_TIPO
--------------------------------------------------------

  ALTER TABLE "SIM"."SOLICITUD_TIPO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."SOLICITUD_TIPO" ADD CONSTRAINT "SOLICITUD_TIPO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."SOLICITUD_TIPO" ADD CONSTRAINT "SOLICITUD_TIPO_UK" UNIQUE ("CODE") ENABLE;
--------------------------------------------------------
--  Constraints for Table SUB_ESTADO
--------------------------------------------------------

  ALTER TABLE "SIM"."SUB_ESTADO" ADD CONSTRAINT "ESTADO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."SUB_ESTADO" MODIFY ("ID_ESTADO" NOT NULL ENABLE);
  ALTER TABLE "SIM"."SUB_ESTADO" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TIPO_ACTIVIDAD
--------------------------------------------------------

  ALTER TABLE "SIM"."TIPO_ACTIVIDAD" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."TIPO_ACTIVIDAD" MODIFY ("TIPO_CLIENTE" NOT NULL ENABLE);
  ALTER TABLE "SIM"."TIPO_ACTIVIDAD" ADD CONSTRAINT "TIPO_ACTIVIDAD_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table TIPO_CLIENTE
--------------------------------------------------------

  ALTER TABLE "SIM"."TIPO_CLIENTE" ADD CONSTRAINT "TIPO_CLIENTE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."TIPO_CLIENTE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TIPO_COMPROBANTE
--------------------------------------------------------

  ALTER TABLE "SIM"."TIPO_COMPROBANTE" MODIFY ("MARCA" NOT NULL ENABLE);
  ALTER TABLE "SIM"."TIPO_COMPROBANTE" MODIFY ("IGV" NOT NULL ENABLE);
  ALTER TABLE "SIM"."TIPO_COMPROBANTE" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."TIPO_COMPROBANTE" MODIFY ("DESCRIPCION" NOT NULL ENABLE);
  ALTER TABLE "SIM"."TIPO_COMPROBANTE" MODIFY ("CTA_SUNAT" NOT NULL ENABLE);
  ALTER TABLE "SIM"."TIPO_COMPROBANTE" ADD CONSTRAINT "TIPO_COMPROBANTE_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table TIPO_GASTO
--------------------------------------------------------

  ALTER TABLE "SIM"."TIPO_GASTO" ADD CONSTRAINT "PK_TIPOGASTO" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table TIPO_INVERSION
--------------------------------------------------------

  ALTER TABLE "SIM"."TIPO_INVERSION" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."TIPO_INVERSION" ADD CONSTRAINT "TIPO_INVERSION_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table TIPO_MONEDA
--------------------------------------------------------

  ALTER TABLE "SIM"."TIPO_MONEDA" ADD CONSTRAINT "PK_TIPO_MONEDA" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table TIPO_PAGO
--------------------------------------------------------

  ALTER TABLE "SIM"."TIPO_PAGO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."TIPO_PAGO" ADD CONSTRAINT "TIPO_PAGO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table TIPO_REGIMEN
--------------------------------------------------------

  ALTER TABLE "SIM"."TIPO_REGIMEN" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."TIPO_REGIMEN" ADD CONSTRAINT "RETENCION_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table USER_TEMPORAL
--------------------------------------------------------

  ALTER TABLE "SIM"."USER_TEMPORAL" ADD CONSTRAINT "USER_TEMPORAL_ID_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SIM"."USER_TEMPORAL" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SIM"."USER_TEMPORAL" MODIFY ("UPDATED_BY" NOT NULL ENABLE);
  ALTER TABLE "SIM"."USER_TEMPORAL" MODIFY ("CREATED_BY" NOT NULL ENABLE);
  ALTER TABLE "SIM"."USER_TEMPORAL" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "SIM"."USER_TEMPORAL" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "SIM"."USER_TEMPORAL" MODIFY ("ID_USER_TEMP" NOT NULL ENABLE);
  ALTER TABLE "SIM"."USER_TEMPORAL" MODIFY ("ID_USER" NOT NULL ENABLE);
--------------------------------------------------------
--  Ref Constraints for Table ASIENTO
--------------------------------------------------------

  ALTER TABLE "SIM"."ASIENTO" ADD CONSTRAINT "ASIENTO_FK1" FOREIGN KEY ("ID_SOLICITUD")
	  REFERENCES "SIM"."SOLICITUD" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table DEPOSITO
--------------------------------------------------------

  ALTER TABLE "SIM"."DEPOSITO" ADD CONSTRAINT "DEPOSITO_FK2" FOREIGN KEY ("NUM_CUENTA")
	  REFERENCES "SIM"."CUENTA" ("NUM_CUENTA") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table EVENT
--------------------------------------------------------

  ALTER TABLE "SIM"."EVENT" ADD CONSTRAINT "EVENT_FK1" FOREIGN KEY ("SOLICITUD_ID")
	  REFERENCES "SIM"."SOLICITUD" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table FILE_STORAGE
--------------------------------------------------------

  ALTER TABLE "SIM"."FILE_STORAGE" ADD CONSTRAINT "FILE_STORAGE_FK1" FOREIGN KEY ("EVENT_ID")
	  REFERENCES "SIM"."EVENT" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table FONDO_CONTABLE_HISTORIA
--------------------------------------------------------

  ALTER TABLE "SIM"."FONDO_CONTABLE_HISTORIA" ADD CONSTRAINT "FONDO_HISTORIA_FK1" FOREIGN KEY ("ID_SOLICITUD")
	  REFERENCES "SIM"."SOLICITUD" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table FONDO_GERENTE_PRODUCTO
--------------------------------------------------------

  ALTER TABLE "SIM"."FONDO_GERENTE_PRODUCTO" ADD CONSTRAINT "FONDOS_FK1" FOREIGN KEY ("SUBCATEGORIA_ID")
	  REFERENCES "SIM"."FONDO_SUBCATEGORIA" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table FONDO_SUBCATEGORIA
--------------------------------------------------------

  ALTER TABLE "SIM"."FONDO_SUBCATEGORIA" ADD CONSTRAINT "FONDOS_SUBCATEGORIAS_FK1" FOREIGN KEY ("ID_FONDO")
	  REFERENCES "SIM"."FONDO_CONTABLE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SIM"."FONDO_SUBCATEGORIA" ADD CONSTRAINT "FONDOS_SUBCATEGORIAS_FK2" FOREIGN KEY ("ID_FONDO_CATEGORIA")
	  REFERENCES "SIM"."FONDO_CATEGORIA" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table FONDO_SUPERVISOR
--------------------------------------------------------

  ALTER TABLE "SIM"."FONDO_SUPERVISOR" ADD CONSTRAINT "FONDOS_SUPERVISOR_FK1" FOREIGN KEY ("SUBCATEGORIA_ID")
	  REFERENCES "SIM"."FONDO_SUBCATEGORIA" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table GASTO
--------------------------------------------------------

  ALTER TABLE "SIM"."GASTO" ADD CONSTRAINT "GASTO_FK1" FOREIGN KEY ("ID_SOLICITUD")
	  REFERENCES "SIM"."SOLICITUD" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table GASTO_ITEM
--------------------------------------------------------

  ALTER TABLE "SIM"."GASTO_ITEM" ADD CONSTRAINT "FK_ID_GASTO" FOREIGN KEY ("ID_GASTO")
	  REFERENCES "SIM"."GASTO" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table INVERSION_ACTIVIDAD
--------------------------------------------------------

  ALTER TABLE "SIM"."INVERSION_ACTIVIDAD" ADD CONSTRAINT "INVERSION_ACTIVIDAD_FK1" FOREIGN KEY ("ID_INVERSION")
	  REFERENCES "SIM"."TIPO_INVERSION" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SIM"."INVERSION_ACTIVIDAD" ADD CONSTRAINT "INVERSION_ACTIVIDAD_FK2" FOREIGN KEY ("ID_ACTIVIDAD")
	  REFERENCES "SIM"."TIPO_ACTIVIDAD" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table INVERSION_POLITICA_APROBACION
--------------------------------------------------------

  ALTER TABLE "SIM"."INVERSION_POLITICA_APROBACION" ADD CONSTRAINT "INVERSION_POLITICA_APROBA_FK1" FOREIGN KEY ("ID_INVERSION")
	  REFERENCES "SIM"."TIPO_INVERSION" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SIM"."INVERSION_POLITICA_APROBACION" ADD CONSTRAINT "INVERSION_POLITICA_APROBA_FK2" FOREIGN KEY ("ID_POLITICA_APROBACION")
	  REFERENCES "SIM"."POLITICA_APROBACION" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table REPORTE_FORMULA
--------------------------------------------------------

  ALTER TABLE "SIM"."REPORTE_FORMULA" ADD CONSTRAINT "REPORTE_FORMULA_FK1" FOREIGN KEY ("QUERY_ID")
	  REFERENCES "SIM"."REPORTE_QUERY" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table REPORTE_USUARIO
--------------------------------------------------------

  ALTER TABLE "SIM"."REPORTE_USUARIO" ADD CONSTRAINT "REPORTE_USUARIO_FK1" FOREIGN KEY ("ID_REPORTE")
	  REFERENCES "SIM"."REPORTE_FORMULA" ("ID_REPORTE") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table SOLICITUD
--------------------------------------------------------

  ALTER TABLE "SIM"."SOLICITUD" ADD CONSTRAINT "SOLICITUD_FK1" FOREIGN KEY ("ID_ESTADO")
	  REFERENCES "SIM"."SUB_ESTADO" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SIM"."SOLICITUD" ADD CONSTRAINT "SOLICITUD_FK2" FOREIGN KEY ("ID_ACTIVIDAD")
	  REFERENCES "SIM"."TIPO_ACTIVIDAD" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SIM"."SOLICITUD" ADD CONSTRAINT "SOLICITUD_FK3" FOREIGN KEY ("ID_INVERSION")
	  REFERENCES "SIM"."TIPO_INVERSION" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SIM"."SOLICITUD" ADD CONSTRAINT "SOLICITUD_FK4" FOREIGN KEY ("IDTIPOSOLICITUD")
	  REFERENCES "SIM"."SOLICITUD_TIPO" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table SOLICITUD_CLIENTE
--------------------------------------------------------

  ALTER TABLE "SIM"."SOLICITUD_CLIENTE" ADD CONSTRAINT "SOLICITUD_CLIENTE_FK1" FOREIGN KEY ("ID_SOLICITUD")
	  REFERENCES "SIM"."SOLICITUD" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SIM"."SOLICITUD_CLIENTE" ADD CONSTRAINT "SOLICITUD_CLIENTE_FK2" FOREIGN KEY ("ID_TIPO_CLIENTE")
	  REFERENCES "SIM"."TIPO_CLIENTE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table SOLICITUD_DETALLE
--------------------------------------------------------

  ALTER TABLE "SIM"."SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_FK1" FOREIGN KEY ("ID")
	  REFERENCES "SIM"."SOLICITUD" ("ID_DETALLE") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SIM"."SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_FK2" FOREIGN KEY ("ID_MONEDA")
	  REFERENCES "SIM"."TIPO_MONEDA" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SIM"."SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_FK3" FOREIGN KEY ("ID_PAGO")
	  REFERENCES "SIM"."TIPO_PAGO" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SIM"."SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_FK4" FOREIGN KEY ("ID_FONDO")
	  REFERENCES "SIM"."FONDO_CONTABLE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SIM"."SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_FK5" FOREIGN KEY ("ID_MOTIVO")
	  REFERENCES "SIM"."MOTIVO" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SIM"."SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_FK6" FOREIGN KEY ("ID_PERIODO")
	  REFERENCES "SIM"."PERIODO" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "SIM"."SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_FK7" FOREIGN KEY ("ID_DEPOSITO")
	  REFERENCES "SIM"."DEPOSITO" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table SOLICITUD_GERENTE
--------------------------------------------------------

  ALTER TABLE "SIM"."SOLICITUD_GERENTE" ADD CONSTRAINT "SOLICITUD_GERENTE_FK1" FOREIGN KEY ("ID_SOLICITUD")
	  REFERENCES "SIM"."SOLICITUD" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table SOLICITUD_HISTORIAL
--------------------------------------------------------

  ALTER TABLE "SIM"."SOLICITUD_HISTORIAL" ADD CONSTRAINT "SOLICITUD_HISTORIAL_FK1" FOREIGN KEY ("ID_SOLICITUD")
	  REFERENCES "SIM"."SOLICITUD" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table SOLICITUD_PRODUCTO
--------------------------------------------------------

  ALTER TABLE "SIM"."SOLICITUD_PRODUCTO" ADD CONSTRAINT "SOLICITUD_PRODUCTO_FK1" FOREIGN KEY ("ID_SOLICITUD")
	  REFERENCES "SIM"."SOLICITUD" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table SUB_ESTADO
--------------------------------------------------------

  ALTER TABLE "SIM"."SUB_ESTADO" ADD CONSTRAINT "SUB_ESTADO_FK1" FOREIGN KEY ("ID_ESTADO")
	  REFERENCES "SIM"."ESTADO" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TIPO_ACTIVIDAD
--------------------------------------------------------

  ALTER TABLE "SIM"."TIPO_ACTIVIDAD" ADD CONSTRAINT "TIPO_ACTIVIDAD_FK1" FOREIGN KEY ("TIPO_CLIENTE")
	  REFERENCES "SIM"."TIPO_CLIENTE" ("ID") ON DELETE CASCADE ENABLE;
