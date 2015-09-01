--------------------------------------------------------
-- Archivo creado  - martes-septiembre-01-2015   
--------------------------------------------------------
DROP TABLE "ASIENTO" cascade constraints;
DROP TABLE "CUENTA" cascade constraints;
DROP TABLE "CUENTA_GASTO_MARCA" cascade constraints;
DROP TABLE "CUENTA_TIPO" cascade constraints;
DROP TABLE "DEPOSITO" cascade constraints;
DROP TABLE "DEVOLUCION" cascade constraints;
DROP TABLE "DOCUMENTO" cascade constraints;
DROP TABLE "ESTADO" cascade constraints;
DROP TABLE "ESTADO_DEVOLUCION" cascade constraints;
DROP TABLE "EVENT" cascade constraints;
DROP TABLE "FILE_STORAGE" cascade constraints;
DROP TABLE "FONDO_CATEGORIA" cascade constraints;
DROP TABLE "FONDO_CONTABLE" cascade constraints;
DROP TABLE "FONDO_GERENTE_PRODUCTO" cascade constraints;
DROP TABLE "FONDO_INSTITUCION" cascade constraints;
DROP TABLE "FONDO_MARKETING_HISTORIA" cascade constraints;
DROP TABLE "FONDO_MARKETING_HISTORIA_RAZON" cascade constraints;
DROP TABLE "FONDO_MKT_PERIODO_HISTORIA" cascade constraints;
DROP TABLE "FONDO_SUBCATEGORIA" cascade constraints;
DROP TABLE "FONDO_SUPERVISOR" cascade constraints;
DROP TABLE "FONDO_TIPO" cascade constraints;
DROP TABLE "GASTO" cascade constraints;
DROP TABLE "GASTO_ITEM" cascade constraints;
DROP TABLE "INVERSION_ACTIVIDAD" cascade constraints;
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
DROP TABLE "TIPO_DEVOLUCION" cascade constraints;
DROP TABLE "TIPO_FONDO_SUBCATEGORIA" cascade constraints;
DROP TABLE "TIPO_GASTO" cascade constraints;
DROP TABLE "TIPO_INSTANCIA_APROBACION" cascade constraints;
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
	"PREFIJO" VARCHAR2(8), 
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
--  DDL for Table DEVOLUCION
--------------------------------------------------------

  CREATE TABLE "DEVOLUCION" 
   (	"ID" NUMBER(*,0), 
	"ID_SOLICITUD" NUMBER(*,0), 
	"NUMERO_OPERACION" VARCHAR2(100 CHAR), 
	"MONTO" NUMBER, 
	"ID_ESTADO_DEVOLUCION" NUMBER(*,0) DEFAULT NULL, 
	"ID_TIPO_DEVOLUCION" NUMBER(*,0) DEFAULT NULL, 
	"CREATED_AT" DATE DEFAULT sysdate, 
	"UPDATED_AT" DATE DEFAULT sysdate, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER(*,0), 
	"UPDATED_BY" NUMBER(*,0), 
	"DELETED_BY" NUMBER(*,0), 
	"PERIODO" NUMBER(*,0)
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
--  DDL for Table ESTADO_DEVOLUCION
--------------------------------------------------------

  CREATE TABLE "ESTADO_DEVOLUCION" 
   (	"ID" NUMBER(*,0), 
	"DESCRIPCION" VARCHAR2(80), 
	"CREATED_AT" DATE DEFAULT sysdate, 
	"UPDATED_AT" DATE DEFAULT sysdate
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
	"CREATED_AT" DATE DEFAULT sysdate, 
	"UPDATED_AT" DATE DEFAULT sysdate, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"SALDO" NUMBER, 
	"RETENCION" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table FONDO_INSTITUCION
--------------------------------------------------------

  CREATE TABLE "FONDO_INSTITUCION" 
   (	"ID" NUMBER(*,0), 
	"SALDO" NUMBER, 
	"SUBCATEGORIA_ID" NUMBER(*,0), 
	"CREATED_AT" DATE DEFAULT sysdate, 
	"UPDATED_AT" DATE DEFAULT sysdate, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER(*,0), 
	"UPDATED_BY" NUMBER(*,0), 
	"DELETED_BY" NUMBER(*,0), 
	"RETENCION" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table FONDO_MARKETING_HISTORIA
--------------------------------------------------------

  CREATE TABLE "FONDO_MARKETING_HISTORIA" 
   (	"ID" NUMBER(*,0), 
	"ID_SOLICITUD" NUMBER(*,0), 
	"ID_TO_FONDO" NUMBER(*,0), 
	"TO_OLD_SALDO" NUMBER, 
	"ID_FONDO_HISTORY_REASON" NUMBER(*,0), 
	"CREATED_AT" DATE, 
	"UPDATED_AT" DATE, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"TO_NEW_SALDO" NUMBER, 
	"ID_TIPO_TO_FONDO" CHAR(1), 
	"OLD_RETENCION" NUMBER, 
	"NEW_RETENCION" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table FONDO_MARKETING_HISTORIA_RAZON
--------------------------------------------------------

  CREATE TABLE "FONDO_MARKETING_HISTORIA_RAZON" 
   (	"ID" NUMBER(*,0), 
	"DESCRIPCION" VARCHAR2(60), 
	"CREATED_AT" DATE DEFAULT sysdate, 
	"UPDATED_AT" DATE DEFAULT sysdate
   ) ;
--------------------------------------------------------
--  DDL for Table FONDO_MKT_PERIODO_HISTORIA
--------------------------------------------------------

  CREATE TABLE "FONDO_MKT_PERIODO_HISTORIA" 
   (	"ID" NUMBER(*,0), 
	"PERIODO" NUMBER(6,0), 
	"SUBCATEGORIA_ID" NUMBER(*,0), 
	"SALDO_INICIAL" NUMBER, 
	"SALDO_FINAL" NUMBER, 
	"CREATED_AT" DATE DEFAULT sysdate, 
	"UPDATED_AT" DATE DEFAULT sysdate, 
	"CREATED_BY" NUMBER(*,0), 
	"UPDATED_BY" NUMBER(*,0), 
	"RETENCION_INICIAL" NUMBER, 
	"RETENCION_FINAL" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table FONDO_SUBCATEGORIA
--------------------------------------------------------

  CREATE TABLE "FONDO_SUBCATEGORIA" 
   (	"ID" NUMBER, 
	"DESCRIPCION" VARCHAR2(200), 
	"ID_FONDO_CATEGORIA" NUMBER, 
	"CREATED_AT" DATE DEFAULT sysdate, 
	"UPDATED_AT" DATE DEFAULT sysdate, 
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
	"CREATED_AT" DATE DEFAULT sysdate, 
	"UPDATED_AT" DATE DEFAULT sysdate, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_AT" DATE, 
	"DELETED_BY" NUMBER(*,0), 
	"RETENCION" NUMBER
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
	"CREATED_AT" DATE DEFAULT sysdate, 
	"UPDATED_AT" DATE DEFAULT sysdate, 
	"CREATED_BY" NUMBER(*,0), 
	"UPDATED_BY" NUMBER(*,0), 
	"DELETED_AT" DATE, 
	"DELETED_BY" NUMBER(*,0)
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
	"CREATED_AT" DATE DEFAULT sysdate, 
	"UPDATED_AT" DATE DEFAULT sysdate, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER(*,0), 
	"UPDATED_BY" NUMBER(*,0), 
	"DELETED_BY" NUMBER(*,0), 
	"ID_TIPO_INSTANCIA_APROBACION" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table REPORTE_FORMULA
--------------------------------------------------------

  CREATE TABLE "REPORTE_FORMULA" 
   (	"ID_REPORTE" NUMBER(*,0), 
	"DESCRIPCION" VARCHAR2(50), 
	"CREATED_AT" DATE DEFAULT sysdate, 
	"UPDATED_AT" DATE DEFAULT sysdate, 
	"QUERY_ID" NUMBER, 
	"FORMULA" VARCHAR2(3000)
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
	"CREATED_AT" DATE DEFAULT sysdate, 
	"UPDATED_AT" DATE DEFAULT sysdate
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
	"COLOR" VARCHAR2(10) DEFAULT '#A4A4A4', 
	"CREATED_AT" DATE DEFAULT sysdate, 
	"UPDATED_AT" DATE DEFAULT sysdate, 
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
--  DDL for Table TIPO_DEVOLUCION
--------------------------------------------------------

  CREATE TABLE "TIPO_DEVOLUCION" 
   (	"ID" NUMBER(*,0), 
	"DESCRIPCION" VARCHAR2(80), 
	"CREATED_AT" DATE DEFAULT sysdate, 
	"UPDATED_AT" DATE DEFAULT sysdate
   ) ;
--------------------------------------------------------
--  DDL for Table TIPO_FONDO_SUBCATEGORIA
--------------------------------------------------------

  CREATE TABLE "TIPO_FONDO_SUBCATEGORIA" 
   (	"ID" NUMBER(*,0), 
	"DESCRIPCION" VARCHAR2(60), 
	"CREATED_AT" DATE DEFAULT sysdate, 
	"UPDATED_AT" DATE DEFAULT sysdate, 
	"CODIGO" CHAR(1 CHAR), 
	"RELACION" VARCHAR2(20)
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
--  DDL for Table TIPO_INSTANCIA_APROBACION
--------------------------------------------------------

  CREATE TABLE "TIPO_INSTANCIA_APROBACION" 
   (	"ID" NUMBER(*,0), 
	"DESCRIPCION" NVARCHAR2(50), 
	"CREATED_AT" DATE DEFAULT sysdate, 
	"UPDATED_AT" DATE DEFAULT sysdate, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER(*,0), 
	"UPDATED_BY" NUMBER(*,0), 
	"DELETED_BY" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TIPO_INVERSION
--------------------------------------------------------

  CREATE TABLE "TIPO_INVERSION" 
   (	"ID" NUMBER, 
	"NOMBRE" VARCHAR2(45), 
	"CREATED_AT" DATE DEFAULT sysdate, 
	"UPDATED_AT" DATE DEFAULT sysdate, 
	"DELETED_AT" DATE, 
	"CREATED_BY" NUMBER, 
	"UPDATED_BY" NUMBER, 
	"DELETED_BY" NUMBER, 
	"CODIGO_ACTIVIDAD" CHAR(1 CHAR) DEFAULT 'O', 
	"ID_FONDO_CONTABLE" NUMBER(*,0), 
	"ID_TIPO_INSTANCIA_APROBACION" NUMBER(*,0)
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
REM INSERTING into CUENTA
SET DEFINE OFF;
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('8','1413370','1','1',to_date('15/04/15 10:12:00','DD/MM/RR HH24:MI:SS'),to_date('15/04/15 10:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('9','1413380','1','1',to_date('15/04/15 10:12:00','DD/MM/RR HH24:MI:SS'),to_date('15/04/15 10:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('48','1413280','1','1',to_date('28/04/15 18:26:40','DD/MM/RR HH24:MI:SS'),to_date('28/04/15 18:26:40','DD/MM/RR HH24:MI:SS'),'43','43');
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('49','1413410','1','1',to_date('29/04/15 17:40:18','DD/MM/RR HH24:MI:SS'),to_date('29/04/15 17:40:18','DD/MM/RR HH24:MI:SS'),'43','43');
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('1','1413270','1','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('2','1413310','1','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('3','1413320','1','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('4','1413330','1','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('5','1413340','1','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('6','1413350','1','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('7','1413360','1','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('22','6371350','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('23','6371330','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('24','6371360','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('25','6371320','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('26','6371340','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('27','6371410','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('28','6371420','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('29','6254000','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('30','6356200','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('31','6594300','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('32','6371430','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('33','6373211','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('34','6373212','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('35','6373213','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('36','6373214','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('37','6373215','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('38','6373221','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('39','6373222','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('40','6373223','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('41','6373224','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('42','6373225','4','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('46','4212100','3','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('43','1041100','2','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('44','1041200','2','2',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('45','4011400','3','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('47','4017200','3','1',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null);
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('54','1413420','1','1',to_date('03/08/15 16:14:15','DD/MM/RR HH24:MI:SS'),to_date('03/08/15 16:14:15','DD/MM/RR HH24:MI:SS'),'43','43');
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('51','1413220','1','1',to_date('01/07/15 18:24:04','DD/MM/RR HH24:MI:SS'),to_date('01/07/15 18:24:04','DD/MM/RR HH24:MI:SS'),'19','19');
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('52','6311222','4','1',to_date('01/07/15 18:24:04','DD/MM/RR HH24:MI:SS'),to_date('01/07/15 18:24:04','DD/MM/RR HH24:MI:SS'),'19','19');
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('53','6313200','4','1',to_date('07/07/15 15:29:36','DD/MM/RR HH24:MI:SS'),to_date('07/07/15 15:29:36','DD/MM/RR HH24:MI:SS'),'19','19');
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('56','1413440','1','1',to_date('03/08/15 16:24:24','DD/MM/RR HH24:MI:SS'),to_date('03/08/15 16:24:24','DD/MM/RR HH24:MI:SS'),'43','43');
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('55','1413430','1','1',to_date('03/08/15 16:15:45','DD/MM/RR HH24:MI:SS'),to_date('03/08/15 16:15:45','DD/MM/RR HH24:MI:SS'),'43','43');
Insert into CUENTA (ID,NUM_CUENTA,IDTIPOCUENTA,IDTIPOMONEDA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY) values ('50','1413450','1','1',to_date('01/07/15 16:41:54','DD/MM/RR HH24:MI:SS'),to_date('01/07/15 16:41:54','DD/MM/RR HH24:MI:SS'),'19','19');
REM INSERTING into CUENTA_GASTO_MARCA
SET DEFINE OFF;
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('48',to_date('10/08/15 17:45:08','DD/MM/RR HH24:MI:SS'),to_date('10/08/15 17:54:42','DD/MM/RR HH24:MI:SS'),'43','43','3',null,null,'1413260','6254000','435020');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('1',null,to_date('10/08/15 15:08:42','DD/MM/RR HH24:MI:SS'),null,'43','1',null,null,'1413270','6594300','431010');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('2',null,null,null,null,'2',null,null,'1413310','6373211','429010');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('3',null,null,null,null,'1',null,null,'1413310','6373211','429010');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('4',null,null,null,null,'3',null,null,'1413310','6373211','429010');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('5',null,null,null,null,'2',null,null,'1413320','6373212','429030');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('6',null,null,null,null,'1',null,null,'1413320','6373212','429030');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('7',null,null,null,null,'3',null,null,'1413320','6373212','429030');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('8',null,null,null,null,'2',null,null,'1413330','6373213','429030');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('9',null,null,null,null,'1',null,null,'1413330','6373213','429030');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('10',null,null,null,null,'3',null,null,'1413330','6373213','429030');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('11',null,null,null,null,'2',null,null,'1413340','6373214','429050');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('12',null,null,null,null,'1',null,null,'1413340','6373214','429050');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('13',null,null,null,null,'3',null,null,'1413340','6373214','429050');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('14',null,null,null,null,'2',null,null,'1413350','6373215','429020');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('15',null,null,null,null,'1',null,null,'1413350','6373215','429020');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('16',null,null,null,null,'3',null,null,'1413350','6373215','429020');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('17',null,null,null,null,'2',null,null,'1413360','6373215','429020');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('18',null,null,null,null,'1',null,null,'1413360','6373215','429020');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('19',null,null,null,null,'3',null,null,'1413360','6373215','429020');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('20',null,null,null,null,'2',null,null,'1413370','6373214','429050');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('21',null,null,null,null,'1',null,null,'1413370','6373214','429050');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('22',null,null,null,null,'3',null,null,'1413370','6373214','429050');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('23',null,null,null,null,'2',null,null,'1413380','6373215','429020');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('24',null,null,null,null,'1',null,null,'1413380','6373215','429020');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('25',null,null,null,null,'3',null,null,'1413380','6373215','429020');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('27',to_date('29/04/15 17:37:00','DD/MM/RR HH24:MI:SS'),to_date('29/04/15 17:37:00','DD/MM/RR HH24:MI:SS'),'43','43','1',null,null,'1413280','6371430','429020');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('29',to_date('07/05/15 10:39:04','DD/MM/RR HH24:MI:SS'),to_date('01/06/15 18:10:40','DD/MM/RR HH24:MI:SS'),'43','43','1',null,null,'1413410','6373221','437130');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('26',to_date('28/04/15 18:26:40','DD/MM/RR HH24:MI:SS'),to_date('28/04/15 18:57:05','DD/MM/RR HH24:MI:SS'),'43','43','2',null,null,'1413280','6371430','429020');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('28',to_date('29/04/15 17:40:18','DD/MM/RR HH24:MI:SS'),to_date('04/05/15 17:40:42','DD/MM/RR HH24:MI:SS'),'43','43','2',null,null,'1413410','6373221','437130');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('44',to_date('03/08/15 16:23:42','DD/MM/RR HH24:MI:SS'),to_date('03/08/15 16:23:42','DD/MM/RR HH24:MI:SS'),'43','43','2',null,null,'1413430','6373223','437010');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('36',to_date('03/08/15 15:55:00','DD/MM/RR HH24:MI:SS'),to_date('03/08/15 15:55:00','DD/MM/RR HH24:MI:SS'),'43','43','2',null,null,'1413270','6594300','431010');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('37',to_date('03/08/15 16:14:15','DD/MM/RR HH24:MI:SS'),to_date('03/08/15 16:14:15','DD/MM/RR HH24:MI:SS'),'43','43','1',null,null,'1413420','6373222','437010');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('30',to_date('02/06/15 15:15:56','DD/MM/RR HH24:MI:SS'),to_date('02/06/15 15:15:56','DD/MM/RR HH24:MI:SS'),'43','43','3',null,null,'1413410','6373221','437130');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('32',to_date('01/07/15 18:24:04','DD/MM/RR HH24:MI:SS'),to_date('01/07/15 18:24:04','DD/MM/RR HH24:MI:SS'),'19','19','2',null,null,'1413220','6311222','418020');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('34',to_date('07/07/15 15:29:36','DD/MM/RR HH24:MI:SS'),to_date('07/07/15 15:29:36','DD/MM/RR HH24:MI:SS'),'19','19','2',null,null,'1413220','6313200','616010');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('39',to_date('03/08/15 16:18:39','DD/MM/RR HH24:MI:SS'),to_date('03/08/15 16:18:39','DD/MM/RR HH24:MI:SS'),'43','43','3',null,null,'1413270','6594300','431010');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('40',to_date('03/08/15 16:21:13','DD/MM/RR HH24:MI:SS'),to_date('03/08/15 16:21:13','DD/MM/RR HH24:MI:SS'),'43','43','3',null,null,'1413280','6371430','429020');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('45',to_date('03/08/15 16:24:24','DD/MM/RR HH24:MI:SS'),to_date('03/08/15 16:24:24','DD/MM/RR HH24:MI:SS'),'43','43','2',null,null,'1413440','6373224','437020');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('46',to_date('03/08/15 16:24:50','DD/MM/RR HH24:MI:SS'),to_date('03/08/15 16:24:50','DD/MM/RR HH24:MI:SS'),'43','43','3',null,null,'1413440','6373224','437020');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('33',to_date('01/07/15 18:26:38','DD/MM/RR HH24:MI:SS'),to_date('01/07/15 18:26:38','DD/MM/RR HH24:MI:SS'),'19','19','1',null,null,'1413220','6311222','616010');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('38',to_date('03/08/15 16:15:45','DD/MM/RR HH24:MI:SS'),to_date('03/08/15 16:15:45','DD/MM/RR HH24:MI:SS'),'43','43','1',null,null,'1413430','6373223','437010');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('41',to_date('03/08/15 16:22:14','DD/MM/RR HH24:MI:SS'),to_date('03/08/15 16:22:14','DD/MM/RR HH24:MI:SS'),'43','43','2',null,null,'1413420','6373222','437010');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('42',to_date('03/08/15 16:22:34','DD/MM/RR HH24:MI:SS'),to_date('03/08/15 16:22:34','DD/MM/RR HH24:MI:SS'),'43','43','3',null,null,'1413420','6373222','437010');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('43',to_date('03/08/15 16:23:11','DD/MM/RR HH24:MI:SS'),to_date('03/08/15 16:23:11','DD/MM/RR HH24:MI:SS'),'43','43','3',null,null,'1413430','6373223','437010');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('47',to_date('03/08/15 16:25:47','DD/MM/RR HH24:MI:SS'),to_date('03/08/15 16:25:47','DD/MM/RR HH24:MI:SS'),'43','43','3',null,null,'1413450','6373225','437140');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('31',to_date('01/07/15 16:41:54','DD/MM/RR HH24:MI:SS'),to_date('01/07/15 16:48:44','DD/MM/RR HH24:MI:SS'),'19','19','2',null,null,'1413450','6373225','437140');
Insert into CUENTA_GASTO_MARCA (ID,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,IDDOCUMENTO,DELETED_AT,DELETED_BY,NUM_CUENTA_FONDO,NUM_CUENTA_GASTO,MARCA_CODIGO) values ('35',to_date('08/07/15 19:11:00','DD/MM/RR HH24:MI:SS'),to_date('08/07/15 19:11:00','DD/MM/RR HH24:MI:SS'),'19','19','1',null,null,'1413450','6373225','437140');
REM INSERTING into CUENTA_TIPO
SET DEFINE OFF;
Insert into CUENTA_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('1','MARKETING',null,null,null,null,null,null);
Insert into CUENTA_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('2','BANCOS',null,null,null,null,null,null);
Insert into CUENTA_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('3','RETENCION',null,null,null,null,null,null);
Insert into CUENTA_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('4','GASTOS',null,null,null,null,null,null);
REM INSERTING into DOCUMENTO
SET DEFINE OFF;
Insert into DOCUMENTO (ID,NOMBRE,CODIGO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('1','FACTURA','F',to_date('06/04/15 15:40:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:40:00','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into DOCUMENTO (ID,NOMBRE,CODIGO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('2','BOLETA','B',to_date('06/04/15 15:40:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:40:00','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into DOCUMENTO (ID,NOMBRE,CODIGO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('3','NO SUSTENTABLE','N',to_date('06/04/15 15:40:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:40:00','DD/MM/RR HH24:MI:SS'),null,null,null,null);
REM INSERTING into ESTADO
SET DEFINE OFF;
Insert into ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('1','PENDIENTE','#f0ad4e','Pendiente de Revision.',to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('2','APROBADO','#5cb85c','Aprobacion de Marketing',to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('3','REVISADO','#0C3BA1','Revision por Contabilidad. y/o Tesorería',to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('5','FINALIZADO','#105F42','Culminada.',to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('6','NO AUTORIZADO','#C43333','Rechazada por Sup. Ger Prod. , Ger. Prom. o Ger Com.',to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('10','TODOS','#2B2828','Todas las Solicitudes.',to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('4','REGISTRO DE GASTOS','#6E6E6E','Descargo de Documentos y Registro de Gastos.',to_date('13/04/15 12:00:00','DD/MM/RR HH24:MI:SS'),null,null,null,null,null);
REM INSERTING into ESTADO_DEVOLUCION
SET DEFINE OFF;
Insert into ESTADO_DEVOLUCION (ID,DESCRIPCION,CREATED_AT,UPDATED_AT) values ('1','POR DEVOLVER',to_date('18/08/15 11:26:02','DD/MM/RR HH24:MI:SS'),to_date('18/08/15 11:26:02','DD/MM/RR HH24:MI:SS'));
Insert into ESTADO_DEVOLUCION (ID,DESCRIPCION,CREATED_AT,UPDATED_AT) values ('2','POR CONFIRMAR',to_date('18/08/15 11:26:02','DD/MM/RR HH24:MI:SS'),to_date('18/08/15 11:26:02','DD/MM/RR HH24:MI:SS'));
Insert into ESTADO_DEVOLUCION (ID,DESCRIPCION,CREATED_AT,UPDATED_AT) values ('3','CONFIRMADO',to_date('18/08/15 11:26:02','DD/MM/RR HH24:MI:SS'),to_date('18/08/15 11:26:02','DD/MM/RR HH24:MI:SS'));
REM INSERTING into FONDO_CATEGORIA
SET DEFINE OFF;
Insert into FONDO_CATEGORIA (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION) values ('1','HERRAMIENTAS DE PROMOCION',null,null,null,null,null,null,'1');
Insert into FONDO_CATEGORIA (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION) values ('2','INVERSIONES',null,null,null,null,null,null,'2');
Insert into FONDO_CATEGORIA (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION) values ('3','INVERSION A FARMACIAS',null,null,null,null,null,null,'3');
Insert into FONDO_CATEGORIA (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION) values ('4','EVENTOS Y PUBLICIDAD',null,null,null,null,null,null,'4');
REM INSERTING into FONDO_CONTABLE
SET DEFINE OFF;
Insert into FONDO_CONTABLE (ID,NOMBRE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,IDTIPOFONDO,NUM_CUENTA,ID_MONEDA) values ('16','EVENTO FARMACIA PROVINCIA',to_date('14/08/15 10:21:33','DD/MM/RR HH24:MI:SS'),to_date('14/08/15 10:21:33','DD/MM/RR HH24:MI:SS'),null,null,null,null,'1','1413440','1');
Insert into FONDO_CONTABLE (ID,NOMBRE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,IDTIPOFONDO,NUM_CUENTA,ID_MONEDA) values ('17','CANJE DE CAJAS',to_date('14/08/15 10:33:43','DD/MM/RR HH24:MI:SS'),to_date('14/08/15 10:33:43','DD/MM/RR HH24:MI:SS'),null,null,null,null,'1','1413230','1');
Insert into FONDO_CONTABLE (ID,NOMBRE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,IDTIPOFONDO,NUM_CUENTA,ID_MONEDA) values ('8','FONDO PROYECTOS ESPECIALES',to_date('15/04/15 10:12:00','DD/MM/RR HH24:MI:SS'),to_date('30/06/15 16:54:10','DD/MM/RR HH24:MI:SS'),null,'19',null,null,'1','1413370','1');
Insert into FONDO_CONTABLE (ID,NOMBRE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,IDTIPOFONDO,NUM_CUENTA,ID_MONEDA) values ('9','INVERSION MARKETING A MEDICOS',to_date('15/04/15 10:12:00','DD/MM/RR HH24:MI:SS'),to_date('28/04/15 11:38:14','DD/MM/RR HH24:MI:SS'),null,'42',null,null,'1','1413380','1');
Insert into FONDO_CONTABLE (ID,NOMBRE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,IDTIPOFONDO,NUM_CUENTA,ID_MONEDA) values ('12','PLAN CONOS',to_date('29/04/15 17:16:18','DD/MM/RR HH24:MI:SS'),to_date('08/07/15 19:12:19','DD/MM/RR HH24:MI:SS'),'42','63',null,null,'1','1413420','1');
Insert into FONDO_CONTABLE (ID,NOMBRE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,IDTIPOFONDO,NUM_CUENTA,ID_MONEDA) values ('13','EVENTO FARMACIA LIMA',to_date('30/04/15 12:44:39','DD/MM/RR HH24:MI:SS'),to_date('03/08/15 16:16:48','DD/MM/RR HH24:MI:SS'),'42','43',null,null,'1','1413430','1');
Insert into FONDO_CONTABLE (ID,NOMBRE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,IDTIPOFONDO,NUM_CUENTA,ID_MONEDA) values ('14','CHARLAS LOCALES CASAM',to_date('07/05/15 10:42:00','DD/MM/RR HH24:MI:SS'),to_date('01/07/15 18:16:46','DD/MM/RR HH24:MI:SS'),'42','19',null,null,'1','1413450','1');
Insert into FONDO_CONTABLE (ID,NOMBRE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,IDTIPOFONDO,NUM_CUENTA,ID_MONEDA) values ('10','PREMIACION POR ROTACION',to_date('28/04/15 12:44:46','DD/MM/RR HH24:MI:SS'),to_date('08/07/15 18:18:49','DD/MM/RR HH24:MI:SS'),'42','42',null,null,'1','1413280','1');
Insert into FONDO_CONTABLE (ID,NOMBRE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,IDTIPOFONDO,NUM_CUENTA,ID_MONEDA) values ('11','EVENTOS MEDICOS',to_date('29/04/15 17:14:21','DD/MM/RR HH24:MI:SS'),to_date('29/05/15 17:02:56','DD/MM/RR HH24:MI:SS'),'42','42',null,null,'1','1413410','1');
Insert into FONDO_CONTABLE (ID,NOMBRE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,IDTIPOFONDO,NUM_CUENTA,ID_MONEDA) values ('1','VINILES',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('10/08/15 14:35:29','DD/MM/RR HH24:MI:SS'),null,'43',null,null,'1','1413270','1');
Insert into FONDO_CONTABLE (ID,NOMBRE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,IDTIPOFONDO,NUM_CUENTA,ID_MONEDA) values ('2','SISOL CENTRAL',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('20/07/15 18:06:07','DD/MM/RR HH24:MI:SS'),null,'42',null,null,'1','1413310','1');
Insert into FONDO_CONTABLE (ID,NOMBRE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,IDTIPOFONDO,NUM_CUENTA,ID_MONEDA) values ('3','FONDO DE ATENCION A CLINICAS',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('20/07/15 17:16:39','DD/MM/RR HH24:MI:SS'),null,'42',null,null,'1','1413320','1');
Insert into FONDO_CONTABLE (ID,NOMBRE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,IDTIPOFONDO,NUM_CUENTA,ID_MONEDA) values ('7','MICROMARKETING A MEDICOS',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('02/07/15 19:29:53','DD/MM/RR HH24:MI:SS'),null,'19',null,null,'1','1413360','1');
Insert into FONDO_CONTABLE (ID,NOMBRE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,IDTIPOFONDO,NUM_CUENTA,ID_MONEDA) values ('5','AREK',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('16/07/15 17:15:41','DD/MM/RR HH24:MI:SS'),null,'42',null,null,'1','1413340','1');
Insert into FONDO_CONTABLE (ID,NOMBRE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,IDTIPOFONDO,NUM_CUENTA,ID_MONEDA) values ('6','INVERSION A FARMACIAS (OTC)',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null,null,null,'1','1413350','1');
Insert into FONDO_CONTABLE (ID,NOMBRE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,IDTIPOFONDO,NUM_CUENTA,ID_MONEDA) values ('4','FONDO COORDINADOR FARMACIAS',to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),to_date('06/04/15 15:12:00','DD/MM/RR HH24:MI:SS'),null,null,null,null,'1','1413330','1');
Insert into FONDO_CONTABLE (ID,NOMBRE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,IDTIPOFONDO,NUM_CUENTA,ID_MONEDA) values ('15','FONDO MOVILIDAD Y GIRA EN PROV',to_date('01/07/15 18:55:33','DD/MM/RR HH24:MI:SS'),to_date('01/07/15 18:56:24','DD/MM/RR HH24:MI:SS'),'19','19',null,null,'1','1413220','1');
REM INSERTING into FONDO_MARKETING_HISTORIA_RAZON
SET DEFINE OFF;
Insert into FONDO_MARKETING_HISTORIA_RAZON (ID,DESCRIPCION,CREATED_AT,UPDATED_AT) values ('1','AJUSTE POR MANTENIMIENTO',to_date('11/08/15 14:10:01','DD/MM/RR HH24:MI:SS'),to_date('11/08/15 14:10:01','DD/MM/RR HH24:MI:SS'));
Insert into FONDO_MARKETING_HISTORIA_RAZON (ID,DESCRIPCION,CREATED_AT,UPDATED_AT) values ('2','RETENCION',to_date('11/08/15 14:10:01','DD/MM/RR HH24:MI:SS'),to_date('11/08/15 14:10:01','DD/MM/RR HH24:MI:SS'));
Insert into FONDO_MARKETING_HISTORIA_RAZON (ID,DESCRIPCION,CREATED_AT,UPDATED_AT) values ('3','LIBERACION',to_date('11/08/15 14:10:01','DD/MM/RR HH24:MI:SS'),to_date('11/08/15 14:10:01','DD/MM/RR HH24:MI:SS'));
Insert into FONDO_MARKETING_HISTORIA_RAZON (ID,DESCRIPCION,CREATED_AT,UPDATED_AT) values ('4','DEPOSITO',to_date('11/08/15 14:10:01','DD/MM/RR HH24:MI:SS'),to_date('11/08/15 14:10:01','DD/MM/RR HH24:MI:SS'));
Insert into FONDO_MARKETING_HISTORIA_RAZON (ID,DESCRIPCION,CREATED_AT,UPDATED_AT) values ('5','DEVOLUCION PLANILLA',to_date('11/08/15 14:10:01','DD/MM/RR HH24:MI:SS'),to_date('11/08/15 14:10:01','DD/MM/RR HH24:MI:SS'));
Insert into FONDO_MARKETING_HISTORIA_RAZON (ID,DESCRIPCION,CREATED_AT,UPDATED_AT) values ('6','TRANSFERENCIA',to_date('11/08/15 14:10:01','DD/MM/RR HH24:MI:SS'),to_date('11/08/15 14:10:01','DD/MM/RR HH24:MI:SS'));
Insert into FONDO_MARKETING_HISTORIA_RAZON (ID,DESCRIPCION,CREATED_AT,UPDATED_AT) values ('7','DEVOLUCION INMEDIATA',to_date('12/08/15 14:18:06','DD/MM/RR HH24:MI:SS'),to_date('12/08/15 14:18:06','DD/MM/RR HH24:MI:SS'));
Insert into FONDO_MARKETING_HISTORIA_RAZON (ID,DESCRIPCION,CREATED_AT,UPDATED_AT) values ('8','DEVOLUCION LIQUIDACION',to_date('27/08/15 16:17:34','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 16:17:34','DD/MM/RR HH24:MI:SS'));
REM INSERTING into FONDO_SUBCATEGORIA
SET DEFINE OFF;
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('1','LITERATURAS','1',null,null,null,null,null,null,'1','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('2','ESTUDIOS CLINICOS','1',null,null,null,null,null,null,'2','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('3','AYUDAS VISUALES','1',null,null,null,null,null,null,'3','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('4','GYMM MEDICOS','1',null,null,null,null,null,null,'4','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('5','FONDO MES CLIN, FCIA, OTROS','1',null,null,null,null,null,null,'5','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('6','CONGRESOS (SOCIEDADES)','2',null,null,null,null,null,null,'1','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('7','MICROMARKETING','2',null,null,null,null,null,null,'2','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('8','INV MKT A MEDICOS','2',null,null,null,null,null,null,'3','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('9','EVENTOS MEDICOS (REUNIONES CIENT.)','2',null,null,null,null,null,null,'4','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('10','FONDO INSTITUCIONES - CAMPAÑAS','2',null,null,null,null,null,null,'5','I');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('11','AREK','2',null,null,null,null,null,null,'6','S');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('12','PROYECTO INSTITUCIONES','2',null,null,null,null,null,null,'7','I');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('13','MAILING','2',null,null,null,null,null,null,'8','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('14','INV MKT A FARMACIAS','3',null,null,null,null,null,null,'1','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('15','PLAN CONOS','3',null,null,null,null,null,null,'2','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('16','PLAN CAPON','3',null,null,null,null,null,null,'3','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('17','PLAN 300','3',null,null,null,null,null,null,'4','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('18','EVENTOS FARMACIAS LIMA','3',null,null,null,null,null,null,'5','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('19','EVENTOS FARMACIAS PROV','3',null,null,null,null,null,null,'6','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('20','COFFES DISTRIBUIDORAS','4',null,null,null,null,null,null,'1','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('21','GYMM FARMACIAS','4',null,null,null,null,null,null,'2','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('22','PUBLICIDAD MASIVA (TV)','4',null,null,null,null,null,null,'3','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('23','PUBLICIDAD DIGITAL','4',null,null,null,null,null,null,'4','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('24','MEDIOS INTERACTIVOS','4',null,null,null,null,null,null,'5','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('25','MATERIAL POP (MERCHANDISING)','4',null,null,null,null,null,null,'6','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('26','PLAN COMP FANTASMA','4',null,null,null,null,null,null,'7','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('27','GANA CON TUS ESTRELLAS','4',null,null,null,null,null,null,'8','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('28','RASPA Y GANA','4',null,null,null,null,null,null,'9','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('29','SAMPLING','4',null,null,null,null,null,null,'10','P');
Insert into FONDO_SUBCATEGORIA (ID,DESCRIPCION,ID_FONDO_CATEGORIA,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,POSITION,TIPO) values ('30','PLAN BODEGAS','4',null,null,null,null,null,null,'11','P');
REM INSERTING into FONDO_TIPO
SET DEFINE OFF;
Insert into FONDO_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('1','PUBLICIDAD Y PROMOCIÓN',null,null,null,null,null,null);
Insert into FONDO_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('2','SIMPOSIOS Y EVENTOS',null,null,null,null,null,null);
Insert into FONDO_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('3','SELECCIÓN Y ENTRENAMIENTO',null,null,null,null,null,null);
Insert into FONDO_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('4','GASTOS DE GIRA',null,null,null,null,null,null);
Insert into FONDO_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('5','MOVILIDAD LIMA',null,null,null,null,null,null);
Insert into FONDO_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('6','MOVILIDAD PROVINCIA',null,null,null,null,null,null);
Insert into FONDO_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('7','GASTOS DE AUTO',null,null,null,null,null,null);
Insert into FONDO_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('8','REUNIONES ZONALES',null,null,null,null,null,null);
Insert into FONDO_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('9','CANJE DE CAJAS',null,null,null,null,null,null);
REM INSERTING into INVERSION_ACTIVIDAD
SET DEFINE OFF;
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('1','1','5',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('2','1','56',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('3','1','57',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('4','1','4',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('5','1','60',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('6','1','59',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('7','1','2',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('8','1','63',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('9','1','6',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('10','1','64',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('11','1','65',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('12','1','9',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('13','1','8',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('14','1','3',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('15','1','1',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('16','1','53',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('17','1','54',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('23','3','62',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('24','3','67',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('25','3','11',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('26','4','13',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('27','4','16',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('28','4','15',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('29','4','12',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('30','4','17',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('31','4','14',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('32','18','68',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('33','18','74',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('34','5','5',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('35','5','4',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('36','5','21',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('37','5','2',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('38','5','20',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('39','5','17',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('40','5','3',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('41','5','1',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('42','19','8',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('43','19','7',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('44','19','53',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('45','19','75',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('54','9','60',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('55','9','63',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('56','9','8',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('57','9','26',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('58','10','8',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('59','10','53',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('60','10','75',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('61','11','59',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('62','11','68',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('63','11','9',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('64','11','71',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('65','11','72',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('66','11','54',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('67','12','5',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('68','12','4',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('69','12','2',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('70','12','20',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('71','12','6',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('72','12','19',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('73','12','12',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('74','12','17',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('75','12','3',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('76','12','22',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('77','12','1',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('78','12','14',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('79','12','18',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('87','15','35',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('88','15','32',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('89','15','36',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('90','15','34',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('91','15','33',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('96','17','5',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('97','17','13',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('98','17','39',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('99','17','21',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('100','17','2',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('101','17','20',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('102','17','15',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('103','17','12',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('104','17','17',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('105','17','3',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('106','17','40',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('107','17','1',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('108','17','14',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('109','17','18',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('110','20','9',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('111','20','72',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('112','20','74',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('113','21','9',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('114','21','72',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('115','21','74',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('116','22','59',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('117','22','68',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('118','22','9',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('119','22','71',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('120','22','72',null,null,null,null,null,null);
Insert into INVERSION_ACTIVIDAD (ID,ID_INVERSION,ID_ACTIVIDAD,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('121','22','54',null,null,null,null,null,null);
REM INSERTING into MANTENIMIENTO
SET DEFINE OFF;
Insert into MANTENIMIENTO (ID,DESCRIPCION,FORMULA) values ('7','Tipo de Inversión','[{"name":"#","key":"id","editable":0},{"name":"Descripcion","key":"nombre","editable":4},{"name":"Fondo ( Contabilidad )","key":"id_fondo_contable","relation":"accountFund","relationKey":"nombre","class":"Fondo_Contable","editable":1},{"name":"Instancia de Aprobación","key":"id_tipo_instancia_aprobacion","relation":"approvalInstance","relationKey":"descripcion","class":"Tipo_Instancia_Aprobacion","editable":1}]');
Insert into MANTENIMIENTO (ID,DESCRIPCION,FORMULA) values ('8','Tipo de Actividad','[{"name":"#","key":"id","editable":0},{"name":"Descripcion","key":"nombre","editable":4},{"name":"Tipo de Cliente","key":"tipo_cliente","relation":"client","relationKey":"descripcion","class":"Tipo_Cliente","editable":1}]');
Insert into MANTENIMIENTO (ID,DESCRIPCION,FORMULA) values ('9','Inversión - Actividad','[{"name":"#","key":"id","editable":0},{"name":"Tipo de Inversión","key":"id_inversion","relation":"investment","relationKey":"nombre","class":"Tipo_Inversion","editable":1},{"name":"Tipo de Actividad","key":"id_actividad","relation":"activity","relationKey":"nombre","class":"Tipo_Actividad","editable":1}]');
Insert into MANTENIMIENTO (ID,DESCRIPCION,FORMULA) values ('1','Cuenta de Fondo, Gasto y Marcas','[{"name":"#","key":"id","editable":0},{"name":"Concepto del Reporte","key":0,"relation":"fondo","relationKey":"nombre","editable":0},{"name":"N° Cuenta de Anticipo","key":"num_cuenta_fondo","editable":3},{"name":"Nombre de Cuenta de Anticipo","key":0,"relation":"bagoAccountFondo","relationKey":"ctanombrecta","editable":0},{"name":"CTATIPOCTA ( Anticipo )","key":0,"relation":"bagoAccountFondo","relationKey":"ctatipocta","editable":0},{"name":"N° Cuenta de Gasto","key":"num_cuenta_gasto","editable":3},{"name":"Nombre de Cuenta de Gasto","key":0,"relation":"bagoAccountExpense","relationKey":"ctanombrecta","editable":0},{"name":"CTATIPOCTA ( Gasto )","key":0,"relation":"bagoAccountExpense","relationKey":"ctatipocta","editable":0},{"name":"Marca","key":"marca_codigo","editable":3},{"name":"Doc","key":"iddocumento","relation":"document","relationKey":"codigo","class":"Documento","editable":1}]');
Insert into MANTENIMIENTO (ID,DESCRIPCION,FORMULA) values ('3','Fondo de Supervisor','[{"name":"#","key":"id","editable":0},{"name":"SubCategoría","key":"subcategoria_id","relation":"subcategoria","relationKey":"descripcion","class":"Fondo_Subcategoria","editable":0},{"name":"Producto","key":"marca_id","relation":"marca","relationKey":"descripcion","class":"Marca","editable":0},{"name":"Supervisor","key":"supervisor_id","relation":"sup","relationKey":"full_name","class":"Personas","editable":0},{"name":"Saldo Contable S/.","key":"saldo","editable":3},{"name":"Monto Reservado S/.","key":"retencion","editable":0}]');
Insert into MANTENIMIENTO (ID,DESCRIPCION,FORMULA) values ('4','Fondo de Gerente de Producto','[{"name":"#","key":"id","editable":0},{"name":"SubCategoría","key":"subcategoria_id","relation":"subcategoria","relationKey":"descripcion","class":"Fondo_Subcategoria","editable":0},{"name":"Producto","key":"marca_id","relation":"marca","relationKey":"descripcion","class":"Marca","editable":0},{"name":"Saldo S/.","key":"saldo","editable":3},{"name":"Monto Reservado S/.","key":"retencion","editable":0}]');
Insert into MANTENIMIENTO (ID,DESCRIPCION,FORMULA) values ('5','Fondo de Institucion','[{"name":"#","key":"id","editable":0},{"name":"SubCategoría","key":"subcategoria_id","relation":"subcategoria","relationKey":"descripcion","class":"Fondo_Subcategoria","editable":0},{"name":"Saldo S/.","key":"saldo","editable":3},{"name":"Monto Retencion S/.","key":"retencion","editable":0}]');
Insert into MANTENIMIENTO (ID,DESCRIPCION,FORMULA) values ('2','Parametros ( Alertas )','[{"name":"#","key":"id","editable":0},{"name":"Descripción","key":"descripcion","editable":4},{"name":"Valor ( Días )","key":"valor","editable":3},{"name":"Mensaje","key":"mensaje","editable":4}]');
Insert into MANTENIMIENTO (ID,DESCRIPCION,FORMULA) values ('6','Fondo Contable','[{"name":"#","key":"id","editable":0},{"name":"Descripcion","key":"nombre","editable":4},{"name":"N° Cuenta","key":"num_cuenta","editable":3}]');
REM INSERTING into MARCA
SET DEFINE OFF;
Insert into MARCA (ID,CODIGO,ID_TIPO_MARCA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('1','429020','1',null,null,null,null,null,null);
Insert into MARCA (ID,CODIGO,ID_TIPO_MARCA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('2','435020','1',null,null,null,null,null,null);
Insert into MARCA (ID,CODIGO,ID_TIPO_MARCA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('3','431010','1',null,null,null,null,null,null);
Insert into MARCA (ID,CODIGO,ID_TIPO_MARCA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('4','429010','1',null,null,null,null,null,null);
Insert into MARCA (ID,CODIGO,ID_TIPO_MARCA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('5','429030','1',null,null,null,null,null,null);
Insert into MARCA (ID,CODIGO,ID_TIPO_MARCA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('6','429050','1',null,null,null,null,null,null);
Insert into MARCA (ID,CODIGO,ID_TIPO_MARCA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('7','437130','1',null,null,null,null,null,null);
Insert into MARCA (ID,CODIGO,ID_TIPO_MARCA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('8','437010','1',null,null,null,null,null,null);
Insert into MARCA (ID,CODIGO,ID_TIPO_MARCA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('9','437020','1',null,null,null,null,null,null);
Insert into MARCA (ID,CODIGO,ID_TIPO_MARCA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('10','435060','1',null,null,null,null,null,null);
Insert into MARCA (ID,CODIGO,ID_TIPO_MARCA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('11','437140','1',null,null,null,null,null,null);
Insert into MARCA (ID,CODIGO,ID_TIPO_MARCA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('12','418020',null,'2015-07-01 18:24:04','2015-07-01 18:24:04','19','19',null,null);
Insert into MARCA (ID,CODIGO,ID_TIPO_MARCA,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('13','616010',null,'2015-07-01 18:26:38','2015-07-01 18:26:38','19','19',null,null);
REM INSERTING into MARCA_TIPO
SET DEFINE OFF;
Insert into MARCA_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('1','ENDO',null,null,null,null,null,null);
Insert into MARCA_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('2','CONTRASTES',null,null,null,null,null,null);
Insert into MARCA_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('3','LEASING',null,null,null,null,null,null);
REM INSERTING into MOTIVO
SET DEFINE OFF;
Insert into MOTIVO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('1','SOLICITUD',null,null,null,null,null,null);
Insert into MOTIVO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('3','REEMBOLSO',null,null,null,null,null,null);
REM INSERTING into PARAMETRO
SET DEFINE OFF;
Insert into PARAMETRO (ID,DESCRIPCION,VALOR,MENSAJE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('4','MONTO DESCUENTO PLANILLA','50','Monto maximo del saldo del descargo para realizarse el descuento por planilla.',to_date('18/08/15 11:51:41','DD/MM/RR HH24:MI:SS'),to_date('18/08/15 11:51:41','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into PARAMETRO (ID,DESCRIPCION,VALOR,MENSAJE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('1','N° DIAS DE LA ALERTA DEL REGISTRO DE GASTOS','7','esta pendiente de realizar el descargo por mas de 1 semana desde que se registro el ultimo documento.',null,to_date('31/08/15 11:48:05','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into PARAMETRO (ID,DESCRIPCION,VALOR,MENSAJE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('2','N° DIAS DE LA ALERTA PARA FINALIZAR EL REGISTRO DE GASTOS','30','No se ha registrado los gastos de la solicitud por mas de 1 mes desde que se creo la solicitud.',null,to_date('31/08/15 11:45:46','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into PARAMETRO (ID,DESCRIPCION,VALOR,MENSAJE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('3','N° DIAS DE LA ALERTA PARA VERIFICAR LA INSTITUCION Y MEDICO','90','tienen por lo menos un cliente medico e institucion iguales:',null,to_date('09/07/15 10:09:25','DD/MM/RR HH24:MI:SS'),null,null,null,null);
REM INSERTING into PERIODO
SET DEFINE OFF;
Insert into PERIODO (ID,ANIOMES,STATUS,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,IDTIPOSOLICITUD) values ('1','201508','1',to_date('25/08/15 14:58:48','DD/MM/RR HH24:MI:SS'),to_date('25/08/15 14:58:48','DD/MM/RR HH24:MI:SS'),null,'44','44',null,'2');
REM INSERTING into POLITICA_APROBACION
SET DEFINE OFF;
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('30',null,null,'1','S',to_date('14/08/15 10:55:17','DD/MM/RR HH24:MI:SS'),to_date('14/08/15 10:55:17','DD/MM/RR HH24:MI:SS'),null,null,null,null,'8');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('31',null,null,'2','P',to_date('14/08/15 10:55:17','DD/MM/RR HH24:MI:SS'),to_date('14/08/15 10:55:17','DD/MM/RR HH24:MI:SS'),null,null,null,null,'8');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('32','0','15000','3','G',to_date('14/08/15 10:55:17','DD/MM/RR HH24:MI:SS'),to_date('14/08/15 10:55:17','DD/MM/RR HH24:MI:SS'),null,null,null,null,'8');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('33','15000',null,'4','GG',to_date('14/08/15 10:55:17','DD/MM/RR HH24:MI:SS'),to_date('14/08/15 10:55:17','DD/MM/RR HH24:MI:SS'),null,null,null,null,'8');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('34','0','300','1','GP',to_date('14/08/15 10:57:10','DD/MM/RR HH24:MI:SS'),to_date('14/08/15 10:57:10','DD/MM/RR HH24:MI:SS'),null,null,null,null,'9');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('35','300','15000','2','G',to_date('14/08/15 10:57:10','DD/MM/RR HH24:MI:SS'),to_date('14/08/15 10:57:10','DD/MM/RR HH24:MI:SS'),null,null,null,null,'9');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('36','15000',null,'3','GG',to_date('14/08/15 10:57:10','DD/MM/RR HH24:MI:SS'),to_date('14/08/15 10:57:10','DD/MM/RR HH24:MI:SS'),null,null,null,null,'9');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('5',null,null,'1','S',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'2');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('14','0','1000','2','P',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'4');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('17','15000',null,'4','GG',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'4');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('21','15000',null,'4','GG',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'5');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('18','0','150','1','S',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'5');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('22',null,null,'1','S',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'6');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('25','15000',null,'4','GG',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'6');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('24','1000','15000','3','G',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'6');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('26',null,null,'1','S',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'7');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('29','15000',null,'4','GG',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'7');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('27',null,null,'2','GP',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'7');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('7','0','300','2','P',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'2');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('13',null,null,'1','S',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'4');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('19','150','1000','2','P',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'5');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('8','300','15000','3','G',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'2');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('4','15000',null,'4','GG',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'1');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('9','15000',null,'4','GG',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'2');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('2','0','300','2','GP',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'1');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('3','300','15000','3','G',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'1');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('16','1000','15000','3','G',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'4');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('28','0','15000','3','G',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'7');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('1',null,null,'1','S',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'1');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('23','0','1000','2','P',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'6');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('20','1000','15000','3','G',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'5');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('11','0','15000','2','G',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'3');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('10',null,null,'1','P',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'3');
Insert into POLITICA_APROBACION (ID,DESDE,HASTA,ORDEN,TIPO_USUARIO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,ID_TIPO_INSTANCIA_APROBACION) values ('12','15000',null,'3','GG',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'3');
REM INSERTING into REPORTE_FORMULA
SET DEFINE OFF;
Insert into REPORTE_FORMULA (ID_REPORTE,DESCRIPCION,CREATED_AT,UPDATED_AT,QUERY_ID,FORMULA) values ('1','Linea de Producto x Monto',to_date('06/07/20 15:12:14','DD/MM/RR HH24:MI:SS'),to_date('06/07/20 15:12:14','DD/MM/RR HH24:MI:SS'),'1','{"headers":["Fecha","Titulo","Tipo_solicitud","Inversion","Rubro","Creado_por","Tipo_usuario","Producto","Cliente","Tipo_cliente","Dias_duracion","Monto_producto","Pais","Area","Zona","Distrito"],"rows":["Linea_producto"],"columns":[],"values":["SUM:Monto_solicitado","SUM:Monto_aprobado"],"frecuency":"N"}');
Insert into REPORTE_FORMULA (ID_REPORTE,DESCRIPCION,CREATED_AT,UPDATED_AT,QUERY_ID,FORMULA) values ('2','Producto x Monto',to_date('06/07/20 15:12:15','DD/MM/RR HH24:MI:SS'),to_date('06/07/20 15:12:15','DD/MM/RR HH24:MI:SS'),'1','{"headers":["Fecha","Titulo","Tipo_solicitud","Inversion","Rubro","Creado_por","Tipo_usuario","Linea_producto","Cliente","Tipo_cliente","Dias_duracion","Monto_producto","Pais","Area","Zona","Distrito"],"rows":["Producto"],"columns":[],"values":["SUM:Monto_solicitado","SUM:Monto_aprobado"],"frecuency":"N"}');
Insert into REPORTE_FORMULA (ID_REPORTE,DESCRIPCION,CREATED_AT,UPDATED_AT,QUERY_ID,FORMULA) values ('3','Rol x Monto',to_date('06/07/20 15:12:16','DD/MM/RR HH24:MI:SS'),to_date('06/07/20 15:12:16','DD/MM/RR HH24:MI:SS'),'1','{"headers":["Fecha","Titulo","Tipo_solicitud","Inversion","Rubro","Creado_por","Producto","Linea_producto","Cliente","Tipo_cliente","Dias_duracion","Monto_producto","Pais","Area","Zona","Distrito"],"rows":["Tipo_usuario"],"columns":[],"values":["SUM:Monto_solicitado","SUM:Monto_aprobado"],"frecuency":"N"}');
Insert into REPORTE_FORMULA (ID_REPORTE,DESCRIPCION,CREATED_AT,UPDATED_AT,QUERY_ID,FORMULA) values ('4','Zona x Monto',to_date('06/07/20 15:12:17','DD/MM/RR HH24:MI:SS'),to_date('06/07/20 15:12:17','DD/MM/RR HH24:MI:SS'),'1','{"headers":["Fecha","Titulo","Tipo_solicitud","Inversion","Rubro","Creado_por","Tipo_usuario","Producto","Linea_producto","Cliente","Monto_producto","Tipo_cliente","Dias_duracion","Pais","Area","Distrito"],"rows":["Zona"],"columns":[],"values":["SUM:Monto_solicitado","SUM:Monto_aprobado"],"frecuency":"N"}');
Insert into REPORTE_FORMULA (ID_REPORTE,DESCRIPCION,CREATED_AT,UPDATED_AT,QUERY_ID,FORMULA) values ('5','Rubro x Monto',to_date('06/07/20 15:12:18','DD/MM/RR HH24:MI:SS'),to_date('06/07/20 15:12:18','DD/MM/RR HH24:MI:SS'),'1','{"headers":["Fecha","Titulo","Tipo_solicitud","Inversion","Creado_por","Tipo_usuario","Producto","Linea_producto","Cliente","Tipo_cliente","Dias_duracion","Monto_producto","Pais","Area","Zona","Distrito"],"rows":["Rubro"],"columns":[],"values":["SUM:Monto_solicitado","SUM:Monto_aprobado"],"frecuency":"N"}');
Insert into REPORTE_FORMULA (ID_REPORTE,DESCRIPCION,CREATED_AT,UPDATED_AT,QUERY_ID,FORMULA) values ('6','Medico x Monto',to_date('06/07/20 15:12:19','DD/MM/RR HH24:MI:SS'),to_date('06/07/20 15:12:19','DD/MM/RR HH24:MI:SS'),'1','{"headers":["Fecha","Titulo","Tipo_solicitud","Inversion","Rubro","Creado_por","Tipo_usuario","Producto","Linea_producto","Monto_producto","Dias_duracion","Pais","Area","Zona","Distrito"],"rows":["Tipo_cliente","Cliente"],"columns":[],"values":["SUM:Monto_solicitado","SUM:Monto_aprobado"],"frecuency":"N"}');
Insert into REPORTE_FORMULA (ID_REPORTE,DESCRIPCION,CREATED_AT,UPDATED_AT,QUERY_ID,FORMULA) values ('7','Duracion de Solicitudes x Inversion',to_date('17/08/15 17:14:43','DD/MM/RR HH24:MI:SS'),to_date('17/08/15 17:14:43','DD/MM/RR HH24:MI:SS'),'1','{"headers":["Fecha","Titulo","Tipo_solicitud","Rubro","Creado_por","Tipo_usuario","Producto","Linea_producto","Cliente","Tipo_cliente","Monto_producto","Monto_solicitado","Monto_aprobado","Pais","Area","Zona","Distrito"],"rows":["Inversion"],"columns":[],"values":["SUM:Dias_duracion"],"frecuency":"N"}');
REM INSERTING into REPORTE_QUERY
SET DEFINE OFF;
Insert into REPORTE_QUERY (ID,NAME) values ('2','Reporte de Solicitudes No Finalizadas');
Insert into REPORTE_QUERY (ID,NAME) values ('1','Reporte de Solicitudes Finalizadas');
REM INSERTING into REPORTE_USUARIO
SET DEFINE OFF;
Insert into REPORTE_USUARIO (ID,ID_REPORTE,ID_USUARIO,CREATED_AT,UPDATED_AT) values ('2','1','199',to_date('17/08/15 17:14:43','DD/MM/RR HH24:MI:SS'),to_date('17/08/15 17:14:43','DD/MM/RR HH24:MI:SS'));
Insert into REPORTE_USUARIO (ID,ID_REPORTE,ID_USUARIO,CREATED_AT,UPDATED_AT) values ('3','2','199',to_date('17/08/15 17:14:43','DD/MM/RR HH24:MI:SS'),to_date('17/08/15 17:14:43','DD/MM/RR HH24:MI:SS'));
Insert into REPORTE_USUARIO (ID,ID_REPORTE,ID_USUARIO,CREATED_AT,UPDATED_AT) values ('4','3','199',to_date('17/08/15 17:14:43','DD/MM/RR HH24:MI:SS'),to_date('17/08/15 17:14:43','DD/MM/RR HH24:MI:SS'));
Insert into REPORTE_USUARIO (ID,ID_REPORTE,ID_USUARIO,CREATED_AT,UPDATED_AT) values ('5','4','199',to_date('17/08/15 17:14:43','DD/MM/RR HH24:MI:SS'),to_date('17/08/15 17:14:43','DD/MM/RR HH24:MI:SS'));
Insert into REPORTE_USUARIO (ID,ID_REPORTE,ID_USUARIO,CREATED_AT,UPDATED_AT) values ('6','5','199',to_date('17/08/15 17:14:43','DD/MM/RR HH24:MI:SS'),to_date('17/08/15 17:14:43','DD/MM/RR HH24:MI:SS'));
Insert into REPORTE_USUARIO (ID,ID_REPORTE,ID_USUARIO,CREATED_AT,UPDATED_AT) values ('7','6','199',to_date('17/08/15 17:14:43','DD/MM/RR HH24:MI:SS'),to_date('17/08/15 17:14:43','DD/MM/RR HH24:MI:SS'));
Insert into REPORTE_USUARIO (ID,ID_REPORTE,ID_USUARIO,CREATED_AT,UPDATED_AT) values ('1','7','199',to_date('17/08/15 17:14:43','DD/MM/RR HH24:MI:SS'),to_date('17/08/15 17:14:43','DD/MM/RR HH24:MI:SS'));
REM INSERTING into SOLICITUD_TIPO
SET DEFINE OFF;
Insert into SOLICITUD_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODE) values ('3','REEMBOLSO',null,null,null,null,null,null,null);
Insert into SOLICITUD_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODE) values ('1','SOLICITUD',null,null,null,null,null,null,'S');
Insert into SOLICITUD_TIPO (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODE) values ('2','INSTITUCIONAL',null,null,null,null,null,null,'F');
REM INSERTING into SUB_ESTADO
SET DEFINE OFF;
Insert into SUB_ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,ID_ESTADO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,DESCRIPCION_MIN) values ('30','TERMINO POR CESE','#EC1E1E','Termina la Solicitud - Liquidado por Cese','5',to_date('27/08/15 21:04:53','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 21:04:57','DD/MM/RR HH24:MI:SS'),null,null,null,null,'Cese - Liquidado');
Insert into SUB_ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,ID_ESTADO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,DESCRIPCION_MIN) values ('10','TODOS','#2B2828','Todas las Solicitudes.','10',to_date('10/09/14 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null,null);
Insert into SUB_ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,ID_ESTADO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,DESCRIPCION_MIN) values ('11','DERIVADO','#5c3bca','Derivado por Sup.','1',to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null,null);
Insert into SUB_ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,ID_ESTADO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,DESCRIPCION_MIN) values ('12','DESCARGO','#2323C4','Contabilidad registra asientos de anticipo y habilia el registro de gasto','4',to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null,'Reg. de Gastos');
Insert into SUB_ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,ID_ESTADO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,DESCRIPCION_MIN) values ('13','CONFIRMADO','#545454','Contbilidad habilita el deposito','3',to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null,'Depósito del Anticipo');
Insert into SUB_ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,ID_ESTADO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,DESCRIPCION_MIN) values ('7','GENERADO','#105F42','Asientos contables registrados.','5',to_date('10/09/14 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null,null);
Insert into SUB_ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,ID_ESTADO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,DESCRIPCION_MIN) values ('2','ACEPTADO','#5cb85c','Aceptada por Sup. o Ger.','2',to_date('10/09/14 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null,'Aprobación');
Insert into SUB_ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,ID_ESTADO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,DESCRIPCION_MIN) values ('9','RECHAZADO','#C43333','Rechazada por Sup. Ger Prod. , Ger Prom. o Ger Com.','6',to_date('10/09/14 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null,null);
Insert into SUB_ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,ID_ESTADO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,DESCRIPCION_MIN) values ('1','PENDIENTE','#f0ad4e','Pendiente de Revision.','1',to_date('10/09/14 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null,'Validación');
Insert into SUB_ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,ID_ESTADO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,DESCRIPCION_MIN) values ('3','APROBADO','#0C3BA1','Aprobado por Ger. Com.','2',to_date('10/09/14 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null,'Validación Cont.');
Insert into SUB_ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,ID_ESTADO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,DESCRIPCION_MIN) values ('4','DEPOSITADO','#428bca','Depósito realizado al Resp.','3',to_date('10/09/14 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null,'Asiento del Anticipo');
Insert into SUB_ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,ID_ESTADO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,DESCRIPCION_MIN) values ('5','REGISTRADO','#433bca','Gastos registrados en el Sis.','4',to_date('10/09/14 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null,'Asiento de Diario');
Insert into SUB_ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,ID_ESTADO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,DESCRIPCION_MIN) values ('6','ENTREGADO','#aa8149','Reporte de gastos adjunto.','4',to_date('10/09/14 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null,null);
Insert into SUB_ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,ID_ESTADO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,DESCRIPCION_MIN) values ('8','CANCELADO','#EC1E1E','Cancelada por Rep. Méd. o Sup.','6',to_date('10/09/14 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('10/02/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null,null);
Insert into SUB_ESTADO (ID,NOMBRE,COLOR,DESCRIPCION,ID_ESTADO,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,DESCRIPCION_MIN) values ('14','DEVOLUCIÓN','#433bca','Tesorería valida la devolucion del monto no sustentado.','4',to_date('23/07/15 11:21:08','DD/MM/RR HH24:MI:SS'),to_date('23/07/15 11:21:08','DD/MM/RR HH24:MI:SS'),null,null,null,null,'Devolución');
REM INSERTING into TIEMPO_ESTIMADO_FLUJO
SET DEFINE OFF;
Insert into TIEMPO_ESTIMADO_FLUJO (ID,STATUS_ID,TO_USER_TYPE,HOURS) values ('1','1','S','8');
Insert into TIEMPO_ESTIMADO_FLUJO (ID,STATUS_ID,TO_USER_TYPE,HOURS) values ('2','2','GP','16');
Insert into TIEMPO_ESTIMADO_FLUJO (ID,STATUS_ID,TO_USER_TYPE,HOURS) values ('3','2','G','8');
Insert into TIEMPO_ESTIMADO_FLUJO (ID,STATUS_ID,TO_USER_TYPE,HOURS) values ('4','3','C','8');
Insert into TIEMPO_ESTIMADO_FLUJO (ID,STATUS_ID,TO_USER_TYPE,HOURS) values ('5','13','T','8');
Insert into TIEMPO_ESTIMADO_FLUJO (ID,STATUS_ID,TO_USER_TYPE,HOURS) values ('6','4','C','8');
Insert into TIEMPO_ESTIMADO_FLUJO (ID,STATUS_ID,TO_USER_TYPE,HOURS) values ('7','12','R','8');
Insert into TIEMPO_ESTIMADO_FLUJO (ID,STATUS_ID,TO_USER_TYPE,HOURS) values ('8','5','C','24');
Insert into TIEMPO_ESTIMADO_FLUJO (ID,STATUS_ID,TO_USER_TYPE,HOURS) values ('9','1','P','16');
REM INSERTING into TIPO_ACTIVIDAD
SET DEFINE OFF;
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('76','Prueba Med 1','#A4A4A4',to_date('10/08/15 10:54:47','DD/MM/RR HH24:MI:SS'),to_date('10/08/15 12:07:11','DD/MM/RR HH24:MI:SS'),null,'199','199','199','2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('77','Prueba Med 2','#A4A4A4',to_date('10/08/15 12:07:32','DD/MM/RR HH24:MI:SS'),to_date('10/08/15 12:07:32','DD/MM/RR HH24:MI:SS'),null,'199','199',null,'3');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('23','RASPA Y GANA','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('1','REGALO MED','#A4A4A4',null,to_date('10/08/15 10:26:45','DD/MM/RR HH24:MI:SS'),null,null,'199','199','1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('2','CAMPAÑA MEDICA MED','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('3','OTROS MED','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('4','ATENCION BREAKS MED','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('5','ALMUERZO MED','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('6','DESAYUNO MED','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('7','PLANES DE ACCION INS','#A4A4A4',null,null,null,null,null,null,'3');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('8','OTROS INS','#A4A4A4',null,null,null,null,null,null,'3');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('9','OTROS FCIA','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('10','OTROS BOD','#A4A4A4',null,null,null,null,null,null,'4');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('11','OTROS DISTRIB','#A4A4A4',null,null,null,null,null,null,'5');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('12','INSCRIPCION','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('13','ALOJAMIENTO','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('14','TRANSPORTE','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('15','CONGRESO FULL','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('16','AUSPICIO','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('17','MOVILIDAD MED','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('18','VALES DE COMPRA MED','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('19','EVENTO EN ZONA MED','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('20','CENA','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('21','ASESORIA / PONENCIA','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('22','PLANES DE ACCION MED','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('24','ACTUALIZACION WEB','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('25','GYMMICK MED','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('26','VOLANTES','#A4A4A4',null,null,null,null,null,null,'3');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('27','PREMIACION MED','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('28','ESTUDIO CLINICO','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('29','AYUDA VISUAL IMPRESA','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('30','LITERATURA','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('31','TRAMITES MED','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('32','LETRERO','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('33','VINIL','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('34','PARANTE','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('35','EXHIBICION','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('36','OTROS POP','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('37','DISEÑO WEB','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('38','OTROS MEDIOS INTERACTIVOS','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('39','ARREGLOS CONSULTORIO','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('40','PAQUETE VIAJE','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('41','RECETARIOS','#A4A4A4',null,null,null,null,null,null,'3');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('42','OTROS PUBLICIDAD','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('43','PRODUCCION NUEVA','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('44','AGENCIA','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('45','SPOT RADIO','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('46','SPOT TV','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('47','ELECTRODOMESTICO','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('48','IMPRESION ESTRELLAS','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('49','CATALOGOS/CARTILLAS','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('50','ABARROTES','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('51','DELIVERY','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('52','BUSQUEDA ESTUDIOS','#A4A4A4',null,null,null,null,null,null,'1');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('53','REGALO INS','#A4A4A4',null,null,null,null,null,null,'3');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('54','REGALO FCIA','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('55','REGALO BOD','#A4A4A4',null,null,null,null,null,null,'4');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('56','ALMUERZO INS','#A4A4A4',null,null,null,null,null,null,'3');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('57','ALMUERZO FCIA','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('58','ALMUERZO BOD','#A4A4A4',null,null,null,null,null,null,'4');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('59','ATENCION BREAKS FCIA','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('60','ATENCION BREAKS INS','#A4A4A4',null,null,null,null,null,null,'3');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('61','ATENCION BREAKS BOD','#A4A4A4',null,null,null,null,null,null,'4');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('62','ATENCION BREAKS DISTRIB','#A4A4A4',null,null,null,null,null,null,'5');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('63','CAMPAÑA MEDICA INS','#A4A4A4',null,null,null,null,null,null,'3');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('64','DESAYUNO INS','#A4A4A4',null,null,null,null,null,null,'3');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('65','DESAYUNO FCIA','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('66','DESAYUNO BOD','#A4A4A4',null,null,null,null,null,null,'4');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('67','DESAYUNO DISTRIB','#A4A4A4',null,null,null,null,null,null,'5');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('68','EVENTO EN ZONA FCIA','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('69','GYMMICK FCIA','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('70','MOVILIDAD FCIA','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('71','PLANES DE ACCION FCIA','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('72','PREMIACION FCIA','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('73','TRAMISTES FCIA','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('74','VALES DE COMPRA FCIA','#A4A4A4',null,null,null,null,null,null,'2');
Insert into TIPO_ACTIVIDAD (ID,NOMBRE,COLOR,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TIPO_CLIENTE) values ('75','VALES DE COMPRA INS','#A4A4A4',null,null,null,null,null,null,'3');
REM INSERTING into TIPO_CLIENTE
SET DEFINE OFF;
Insert into TIPO_CLIENTE (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,NUM_MEDICO,RELACION) values ('1','MEDICO',null,null,null,null,null,null,'0','doctor');
Insert into TIPO_CLIENTE (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,NUM_MEDICO,RELACION) values ('2','FARMACIA',null,null,null,null,null,null,'1','pharmacy');
Insert into TIPO_CLIENTE (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,NUM_MEDICO,RELACION) values ('3','INSTITUCION',null,null,null,null,null,null,'2','institution');
Insert into TIPO_CLIENTE (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,NUM_MEDICO,RELACION) values ('4','BODEGA',null,null,null,null,null,null,'0','warehouse');
Insert into TIPO_CLIENTE (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,NUM_MEDICO,RELACION) values ('5','DISTRIBUIDOR',null,null,null,null,null,null,'0','distributor');
REM INSERTING into TIPO_COMPROBANTE
SET DEFINE OFF;
Insert into TIPO_COMPROBANTE (ID,DESCRIPCION,CTA_SUNAT,MARCA,IGV,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('1','FACTURA','01','F,F','1',null,to_date('09/07/15 10:42:48','DD/MM/RR HH24:MI:SS'),null,null,'43',null);
Insert into TIPO_COMPROBANTE (ID,DESCRIPCION,CTA_SUNAT,MARCA,IGV,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('2','RECIBO POR HONORARIOS','02','F','0',null,null,null,null,null,null);
Insert into TIPO_COMPROBANTE (ID,DESCRIPCION,CTA_SUNAT,MARCA,IGV,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('3','BOLETA DE VENTA','03','B','0',null,null,null,null,null,null);
Insert into TIPO_COMPROBANTE (ID,DESCRIPCION,CTA_SUNAT,MARCA,IGV,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('4','TICKET','12','B,F','1',null,null,null,null,null,null);
Insert into TIPO_COMPROBANTE (ID,DESCRIPCION,CTA_SUNAT,MARCA,IGV,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('5','BOLETOS DE VIAJE','16','B','0',null,null,null,null,null,null);
Insert into TIPO_COMPROBANTE (ID,DESCRIPCION,CTA_SUNAT,MARCA,IGV,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('6','REVISIÓN TÉCNICA','37','B','0',null,to_date('01/06/15 17:48:05','DD/MM/RR HH24:MI:SS'),null,null,'43',null);
Insert into TIPO_COMPROBANTE (ID,DESCRIPCION,CTA_SUNAT,MARCA,IGV,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('7','OTROS','00','N','0',null,to_date('03/06/15 13:56:11','DD/MM/RR HH24:MI:SS'),null,null,'19',null);
REM INSERTING into TIPO_DEVOLUCION
SET DEFINE OFF;
Insert into TIPO_DEVOLUCION (ID,DESCRIPCION,CREATED_AT,UPDATED_AT) values ('1','INMEDIATA',to_date('18/08/15 11:30:53','DD/MM/RR HH24:MI:SS'),to_date('18/08/15 11:30:53','DD/MM/RR HH24:MI:SS'));
Insert into TIPO_DEVOLUCION (ID,DESCRIPCION,CREATED_AT,UPDATED_AT) values ('2','PLANILLA',to_date('18/08/15 11:30:53','DD/MM/RR HH24:MI:SS'),to_date('18/08/15 11:30:53','DD/MM/RR HH24:MI:SS'));
Insert into TIPO_DEVOLUCION (ID,DESCRIPCION,CREATED_AT,UPDATED_AT) values ('3','LIQUIDACION',to_date('27/08/15 12:04:26','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 12:04:26','DD/MM/RR HH24:MI:SS'));
REM INSERTING into TIPO_FONDO_SUBCATEGORIA
SET DEFINE OFF;
Insert into TIPO_FONDO_SUBCATEGORIA (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,CODIGO,RELACION) values ('1','SUBCATEGORÍA SUPERVISOR',to_date('12/08/15 10:07:12','DD/MM/RR HH24:MI:SS'),to_date('12/08/15 10:07:12','DD/MM/RR HH24:MI:SS'),'S','fromSupFund');
Insert into TIPO_FONDO_SUBCATEGORIA (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,CODIGO,RELACION) values ('2','SUBCATEGORÍA GERENTE PRODUCTO',to_date('12/08/15 10:07:12','DD/MM/RR HH24:MI:SS'),to_date('12/08/15 10:07:12','DD/MM/RR HH24:MI:SS'),'P','fromGerProdFund');
Insert into TIPO_FONDO_SUBCATEGORIA (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,CODIGO,RELACION) values ('3','SUBCATEGORÍA INSTITUCIONES',to_date('12/08/15 10:07:12','DD/MM/RR HH24:MI:SS'),to_date('12/08/15 10:07:12','DD/MM/RR HH24:MI:SS'),'I','fromInstitutionFund');
REM INSERTING into TIPO_GASTO
SET DEFINE OFF;
Insert into TIPO_GASTO (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('1','ALIMENTACION',null,null,null,null,null,null);
Insert into TIPO_GASTO (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('2','VIAJES',null,null,null,null,null,null);
Insert into TIPO_GASTO (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('4','VIATICOS',null,null,null,null,null,null);
Insert into TIPO_GASTO (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('5','OTROS',null,null,null,null,null,null);
Insert into TIPO_GASTO (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('3','HOSPEDAJE',null,null,null,null,null,null);
REM INSERTING into TIPO_INSTANCIA_APROBACION
SET DEFINE OFF;
Insert into TIPO_INSTANCIA_APROBACION (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('10','SIN FLUJO DE APROBACIÓN',to_date('17/08/15 17:17:00','DD/MM/RR HH24:MI:SS'),to_date('17/08/15 17:17:00','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into TIPO_INSTANCIA_APROBACION (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('8','PREMIACION POR ROTACION',to_date('14/08/15 10:53:02','DD/MM/RR HH24:MI:SS'),to_date('14/08/15 10:53:02','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into TIPO_INSTANCIA_APROBACION (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('9','CHARLAS LOCALES SAM',to_date('14/08/15 10:55:52','DD/MM/RR HH24:MI:SS'),to_date('14/08/15 10:55:52','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into TIPO_INSTANCIA_APROBACION (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('1','CAMPAÑAS',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into TIPO_INSTANCIA_APROBACION (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('2','OTC',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into TIPO_INSTANCIA_APROBACION (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('3','EVENTOS CONG GYMN MAIL',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into TIPO_INSTANCIA_APROBACION (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('4','INV MKT OTC',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into TIPO_INSTANCIA_APROBACION (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('5','INV MKT',to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:36:57','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into TIPO_INSTANCIA_APROBACION (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('6','MICROMKT',to_date('07/08/15 13:41:53','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 13:41:53','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into TIPO_INSTANCIA_APROBACION (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('7','CONCURSOS',to_date('07/08/15 14:01:41','DD/MM/RR HH24:MI:SS'),to_date('07/08/15 14:01:41','DD/MM/RR HH24:MI:SS'),null,null,null,null);
REM INSERTING into TIPO_INVERSION
SET DEFINE OFF;
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('29','EVENTOS FARMACIAS PROVINCIAS',to_date('14/08/15 10:23:09','DD/MM/RR HH24:MI:SS'),to_date('14/08/15 10:23:09','DD/MM/RR HH24:MI:SS'),null,null,null,null,'M','16','2');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('30','FONDO MES CLINICA',to_date('14/08/15 10:25:58','DD/MM/RR HH24:MI:SS'),to_date('14/08/15 10:25:58','DD/MM/RR HH24:MI:SS'),null,null,null,null,'F','3','1');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('31','GASTO DE GIRA',to_date('14/08/15 10:25:58','DD/MM/RR HH24:MI:SS'),to_date('14/08/15 10:25:58','DD/MM/RR HH24:MI:SS'),null,null,null,null,'F','15','1');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('32','PREMIACION POR ROTACION (FCIAS)',to_date('14/08/15 10:30:38','DD/MM/RR HH24:MI:SS'),to_date('14/08/15 10:30:38','DD/MM/RR HH24:MI:SS'),null,null,null,null,'M','10','8');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('34','CANJE DE CAJAS (FCIAS)',to_date('14/08/15 10:34:38','DD/MM/RR HH24:MI:SS'),to_date('14/08/15 10:34:38','DD/MM/RR HH24:MI:SS'),null,null,null,null,'F','17','1');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('35','CHARLAS / TELEMARKETING LOCALES SAM',to_date('14/08/15 10:59:14','DD/MM/RR HH24:MI:SS'),to_date('14/08/15 10:59:14','DD/MM/RR HH24:MI:SS'),null,null,null,null,'M','14','9');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('20','PLAN 300 (FCIAS)',to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),null,null,null,null,'M','7','4');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('1','AREK',to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),to_date('10/08/15 11:34:21','DD/MM/RR HH24:MI:SS'),null,null,'199',null,'M','5','1');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('3','COFFES DISTRIBUIDORAS (FCIAS)',to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),null,null,null,null,'M','2','2');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('4','CONGRESOS',to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),null,null,null,null,'O','15','3');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('5','EVENTOS MEDICOS',to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),null,null,null,null,'M','11','3');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('9','INSTITUCIONES - CAMPAÑAS',to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),null,null,null,null,'M','2','1');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('10','INSTITUCIONES - CONCURSOS',to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),null,null,null,null,'M','2','7');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('11','INV MARKETING (FCIAS)',to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),null,null,null,null,'M','6','4');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('12','INV MARKETING MEDICOS',to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),null,null,null,null,'M','9','5');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('15','MATERIAL POP (FCIAS)',to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),null,null,null,null,'M','1','4');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('17','MICROMARKETING',to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),null,null,null,null,'M','7','6');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('18','EVENTOS FARMACIAS  LIMA',to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),null,null,null,null,'M','13','2');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('19','FONDO INSTITUCIONAL',to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),null,null,null,null,'I','2','1');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('21','PLAN CAPON (FCIAS)',to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),null,null,null,null,'M','7','4');
Insert into TIPO_INVERSION (ID,NOMBRE,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,CODIGO_ACTIVIDAD,ID_FONDO_CONTABLE,ID_TIPO_INSTANCIA_APROBACION) values ('22','PLAN CONOS (FCIAS)',to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),to_date('01/08/15 12:13:45','DD/MM/RR HH24:MI:SS'),null,null,null,null,'M','12','4');
REM INSERTING into TIPO_MONEDA
SET DEFINE OFF;
Insert into TIPO_MONEDA (ID,DESCRIPCION,SIMBOLO,CREATED_AT,UPDATED_AT) values ('1','SOLES','S/.',null,null);
Insert into TIPO_MONEDA (ID,DESCRIPCION,SIMBOLO,CREATED_AT,UPDATED_AT) values ('2','DOLARES','$',null,null);
REM INSERTING into TIPO_PAGO
SET DEFINE OFF;
Insert into TIPO_PAGO (ID,NOMBRE,CREATED_AT,UPDATED_AT) values ('1','TRANSFERENCIA',null,null);
Insert into TIPO_PAGO (ID,NOMBRE,CREATED_AT,UPDATED_AT) values ('2','CHEQUE',null,null);
REM INSERTING into TIPO_REGIMEN
SET DEFINE OFF;
Insert into TIPO_REGIMEN (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('1','RETENCION',null,null,null,null,null,null);
Insert into TIPO_REGIMEN (ID,DESCRIPCION,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY) values ('2','DETRACCION',null,null,null,null,null,null);
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
--  DDL for Index DEPOSITO_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "DEPOSITO_UK1" ON "DEPOSITO" ("NUM_TRANSFERENCIA") 
  ;
--------------------------------------------------------
--  DDL for Index DEVOLUCION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "DEVOLUCION_PK" ON "DEVOLUCION" ("ID") 
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
--  DDL for Index ESTADO_DEVOLUCION_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "ESTADO_DEVOLUCION_UK1" ON "ESTADO_DEVOLUCION" ("ID", "DESCRIPCION") 
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
--  DDL for Index FONDO_MARKETING_HISTORIA_R_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "FONDO_MARKETING_HISTORIA_R_PK" ON "FONDO_MARKETING_HISTORIA_RAZON" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONDO_MKT_PERIODO_HISTORIA_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "FONDO_MKT_PERIODO_HISTORIA_PK" ON "FONDO_MKT_PERIODO_HISTORIA" ("ID") 
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

  CREATE UNIQUE INDEX "POLITICA_APROBACION_UK1" ON "POLITICA_APROBACION" ("DESDE", "HASTA", "ORDEN", "TIPO_USUARIO", "ID_TIPO_INSTANCIA_APROBACION") 
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
--  DDL for Index TABLE1_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "TABLE1_PK" ON "ESTADO_DEVOLUCION" ("ID") 
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
--  DDL for Index TIPO_DEVOLUCION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "TIPO_DEVOLUCION_PK" ON "TIPO_DEVOLUCION" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TIPO_DEVOLUCION_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "TIPO_DEVOLUCION_UK1" ON "TIPO_DEVOLUCION" ("ID", "DESCRIPCION") 
  ;
--------------------------------------------------------
--  DDL for Index TIPO_FONDO_SUBCATEGORIA_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "TIPO_FONDO_SUBCATEGORIA_PK" ON "TIPO_FONDO_SUBCATEGORIA" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TIPO_INSTANCIA_APROBACION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "TIPO_INSTANCIA_APROBACION_PK" ON "TIPO_INSTANCIA_APROBACION" ("ID") 
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
--  DDL for Index USER_TEMPORAL_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "USER_TEMPORAL_UK1" ON "USER_TEMPORAL" ("DELETED_AT", "ID_USER") 
  ;
--------------------------------------------------------
--  Constraints for Table ASIENTO
--------------------------------------------------------

  ALTER TABLE "ASIENTO" MODIFY ("ID_SOLICITUD" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" MODIFY ("NUM_CUENTA" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" MODIFY ("FEC_ORIGEN" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" MODIFY ("D_C" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" MODIFY ("IMPORTE" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" MODIFY ("CREATED_BY" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" MODIFY ("UPDATED_BY" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "ASIENTO" ADD CONSTRAINT "ASIENTO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "ASIENTO" MODIFY ("TIPO_ASIENTO" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table CUENTA
--------------------------------------------------------

  ALTER TABLE "CUENTA" MODIFY ("NUM_CUENTA" NOT NULL ENABLE);
  ALTER TABLE "CUENTA" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "CUENTA" ADD CONSTRAINT "DMKT2_RG_CUENTA_UK" UNIQUE ("NUM_CUENTA") ENABLE;
  ALTER TABLE "CUENTA" ADD CONSTRAINT "DMKT2_RG_CUENTA_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table CUENTA_GASTO_MARCA
--------------------------------------------------------

  ALTER TABLE "CUENTA_GASTO_MARCA" ADD CONSTRAINT "CUENTA_MARCA_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "CUENTA_GASTO_MARCA" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "CUENTA_GASTO_MARCA" ADD CONSTRAINT "CUENTA_GASTO_MARC_UK2" UNIQUE ("NUM_CUENTA_FONDO", "NUM_CUENTA_GASTO", "MARCA_CODIGO", "IDDOCUMENTO") ENABLE;
--------------------------------------------------------
--  Constraints for Table CUENTA_TIPO
--------------------------------------------------------

  ALTER TABLE "CUENTA_TIPO" ADD CONSTRAINT "CUENTA_TIPO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "CUENTA_TIPO" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table DEPOSITO
--------------------------------------------------------

  ALTER TABLE "DEPOSITO" ADD CONSTRAINT "DEPOSITO_UK1" UNIQUE ("NUM_TRANSFERENCIA") ENABLE;
  ALTER TABLE "DEPOSITO" MODIFY ("TOTAL" NOT NULL ENABLE);
  ALTER TABLE "DEPOSITO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "DEPOSITO" ADD CONSTRAINT "PK_CODDEPOSITO" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "DEPOSITO" MODIFY ("NUM_TRANSFERENCIA" NOT NULL ENABLE);
  ALTER TABLE "DEPOSITO" MODIFY ("NUM_CUENTA" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table DEVOLUCION
--------------------------------------------------------

  ALTER TABLE "DEVOLUCION" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "DEVOLUCION" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "DEVOLUCION" ADD CONSTRAINT "DEVOLUCION_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "DEVOLUCION" MODIFY ("ID_TIPO_DEVOLUCION" NOT NULL ENABLE);
  ALTER TABLE "DEVOLUCION" MODIFY ("ID_ESTADO_DEVOLUCION" NOT NULL ENABLE);
  ALTER TABLE "DEVOLUCION" MODIFY ("MONTO" NOT NULL ENABLE);
  ALTER TABLE "DEVOLUCION" MODIFY ("ID_SOLICITUD" NOT NULL ENABLE);
  ALTER TABLE "DEVOLUCION" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table DOCUMENTO
--------------------------------------------------------

  ALTER TABLE "DOCUMENTO" ADD CONSTRAINT "DOCUMENTO_UK" UNIQUE ("CODIGO") ENABLE;
  ALTER TABLE "DOCUMENTO" ADD CONSTRAINT "DOCUMENTO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "DOCUMENTO" MODIFY ("CODIGO" NOT NULL ENABLE);
  ALTER TABLE "DOCUMENTO" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table ESTADO
--------------------------------------------------------

  ALTER TABLE "ESTADO" ADD CONSTRAINT "ESTADO_RANGO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "ESTADO" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table ESTADO_DEVOLUCION
--------------------------------------------------------

  ALTER TABLE "ESTADO_DEVOLUCION" ADD CONSTRAINT "ESTADO_DEVOLUCION_UK1" UNIQUE ("ID", "DESCRIPCION") ENABLE;
  ALTER TABLE "ESTADO_DEVOLUCION" ADD CONSTRAINT "ESTADO_DEVOLUCION_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "ESTADO_DEVOLUCION" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "ESTADO_DEVOLUCION" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "ESTADO_DEVOLUCION" MODIFY ("DESCRIPCION" NOT NULL ENABLE);
  ALTER TABLE "ESTADO_DEVOLUCION" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table EVENT
--------------------------------------------------------

  ALTER TABLE "EVENT" ADD CONSTRAINT "EVENT_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "EVENT" MODIFY ("DESCRIPTION" NOT NULL ENABLE);
  ALTER TABLE "EVENT" MODIFY ("NAME" NOT NULL ENABLE);
  ALTER TABLE "EVENT" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "EVENT" MODIFY ("EVENT_DATE" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FILE_STORAGE
--------------------------------------------------------

  ALTER TABLE "FILE_STORAGE" ADD CONSTRAINT "FILE_STORAGE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "FILE_STORAGE" MODIFY ("APP" NOT NULL ENABLE);
  ALTER TABLE "FILE_STORAGE" MODIFY ("DIRECTORY" NOT NULL ENABLE);
  ALTER TABLE "FILE_STORAGE" MODIFY ("EXTENSION" NOT NULL ENABLE);
  ALTER TABLE "FILE_STORAGE" MODIFY ("NAME" NOT NULL ENABLE);
  ALTER TABLE "FILE_STORAGE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FONDO_CATEGORIA
--------------------------------------------------------

  ALTER TABLE "FONDO_CATEGORIA" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_CATEGORIA" ADD CONSTRAINT "FONDOS_CATEGORIAS_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table FONDO_CONTABLE
--------------------------------------------------------

  ALTER TABLE "FONDO_CONTABLE" MODIFY ("ID_MONEDA" NOT NULL ENABLE);
  ALTER TABLE "FONDO_CONTABLE" ADD CONSTRAINT "FONDO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "FONDO_CONTABLE" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_CONTABLE" MODIFY ("NUM_CUENTA" NOT NULL ENABLE);
  ALTER TABLE "FONDO_CONTABLE" MODIFY ("IDTIPOFONDO" NOT NULL ENABLE);
  ALTER TABLE "FONDO_CONTABLE" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "FONDO_CONTABLE" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "FONDO_CONTABLE" MODIFY ("NOMBRE" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FONDO_GERENTE_PRODUCTO
--------------------------------------------------------

  ALTER TABLE "FONDO_GERENTE_PRODUCTO" MODIFY ("SALDO" NOT NULL ENABLE);
  ALTER TABLE "FONDO_GERENTE_PRODUCTO" MODIFY ("RETENCION" NOT NULL ENABLE);
  ALTER TABLE "FONDO_GERENTE_PRODUCTO" ADD CONSTRAINT "FONDOS_UK1" UNIQUE ("SUBCATEGORIA_ID", "MARCA_ID") ENABLE;
  ALTER TABLE "FONDO_GERENTE_PRODUCTO" MODIFY ("SUBCATEGORIA_ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_GERENTE_PRODUCTO" MODIFY ("MARCA_ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_GERENTE_PRODUCTO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_GERENTE_PRODUCTO" ADD CONSTRAINT "FONDOS_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table FONDO_INSTITUCION
--------------------------------------------------------

  ALTER TABLE "FONDO_INSTITUCION" MODIFY ("RETENCION" NOT NULL ENABLE);
  ALTER TABLE "FONDO_INSTITUCION" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_INSTITUCION" MODIFY ("SALDO" NOT NULL ENABLE);
  ALTER TABLE "FONDO_INSTITUCION" MODIFY ("SUBCATEGORIA_ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_INSTITUCION" ADD CONSTRAINT "FONDO_INSTITUCION_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "FONDO_INSTITUCION" ADD CONSTRAINT "FONDO_INSTITUCION_UK1" UNIQUE ("SUBCATEGORIA_ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table FONDO_MARKETING_HISTORIA
--------------------------------------------------------

  ALTER TABLE "FONDO_MARKETING_HISTORIA" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_MARKETING_HISTORIA" ADD CONSTRAINT "FONDO_MARKETING_HISTORIA_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "FONDO_MARKETING_HISTORIA" MODIFY ("ID_FONDO_HISTORY_REASON" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FONDO_MARKETING_HISTORIA_RAZON
--------------------------------------------------------

  ALTER TABLE "FONDO_MARKETING_HISTORIA_RAZON" ADD CONSTRAINT "FONDO_MARKETING_HISTORIA_R_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "FONDO_MARKETING_HISTORIA_RAZON" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "FONDO_MARKETING_HISTORIA_RAZON" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "FONDO_MARKETING_HISTORIA_RAZON" MODIFY ("DESCRIPCION" NOT NULL ENABLE);
  ALTER TABLE "FONDO_MARKETING_HISTORIA_RAZON" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FONDO_MKT_PERIODO_HISTORIA
--------------------------------------------------------

  ALTER TABLE "FONDO_MKT_PERIODO_HISTORIA" ADD CONSTRAINT "FONDO_MKT_PERIODO_HISTORIA_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "FONDO_MKT_PERIODO_HISTORIA" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "FONDO_MKT_PERIODO_HISTORIA" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "FONDO_MKT_PERIODO_HISTORIA" MODIFY ("SALDO_FINAL" NOT NULL ENABLE);
  ALTER TABLE "FONDO_MKT_PERIODO_HISTORIA" MODIFY ("SALDO_INICIAL" NOT NULL ENABLE);
  ALTER TABLE "FONDO_MKT_PERIODO_HISTORIA" MODIFY ("SUBCATEGORIA_ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_MKT_PERIODO_HISTORIA" MODIFY ("PERIODO" NOT NULL ENABLE);
  ALTER TABLE "FONDO_MKT_PERIODO_HISTORIA" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_MKT_PERIODO_HISTORIA" MODIFY ("UPDATED_BY" NOT NULL ENABLE);
  ALTER TABLE "FONDO_MKT_PERIODO_HISTORIA" MODIFY ("CREATED_BY" NOT NULL ENABLE);
  ALTER TABLE "FONDO_MKT_PERIODO_HISTORIA" MODIFY ("RETENCION_FINAL" NOT NULL ENABLE);
  ALTER TABLE "FONDO_MKT_PERIODO_HISTORIA" MODIFY ("RETENCION_INICIAL" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FONDO_SUBCATEGORIA
--------------------------------------------------------

  ALTER TABLE "FONDO_SUBCATEGORIA" MODIFY ("ID_FONDO_CATEGORIA" NOT NULL ENABLE);
  ALTER TABLE "FONDO_SUBCATEGORIA" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_SUBCATEGORIA" ADD CONSTRAINT "FONDOS_SUBCATEGORIAS_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table FONDO_SUPERVISOR
--------------------------------------------------------

  ALTER TABLE "FONDO_SUPERVISOR" MODIFY ("SUBCATEGORIA_ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_SUPERVISOR" MODIFY ("MARCA_ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_SUPERVISOR" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "FONDO_SUPERVISOR" ADD CONSTRAINT "FONDOS_SUPERVISOR_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "FONDO_SUPERVISOR" ADD CONSTRAINT "FONDOS_SUPERVISOR_UK1" UNIQUE ("SUPERVISOR_ID", "MARCA_ID", "SUBCATEGORIA_ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table FONDO_TIPO
--------------------------------------------------------

  ALTER TABLE "FONDO_TIPO" ADD CONSTRAINT "FONDO_TIPO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "FONDO_TIPO" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table GASTO
--------------------------------------------------------

  ALTER TABLE "GASTO" ADD CONSTRAINT "GASTO_CHK2" CHECK (NUM_SERIE >= 1) ENABLE;
  ALTER TABLE "GASTO" MODIFY ("IDCOMPROBANTE" NOT NULL ENABLE);
  ALTER TABLE "GASTO" MODIFY ("ID_SOLICITUD" NOT NULL ENABLE);
  ALTER TABLE "GASTO" ADD CONSTRAINT "PK_ID_GASTO" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table GASTO_ITEM
--------------------------------------------------------

  ALTER TABLE "GASTO_ITEM" MODIFY ("CANTIDAD" NOT NULL ENABLE);
  ALTER TABLE "GASTO_ITEM" MODIFY ("TIPO_GASTO" NOT NULL ENABLE);
  ALTER TABLE "GASTO_ITEM" MODIFY ("IMPORTE" NOT NULL ENABLE);
  ALTER TABLE "GASTO_ITEM" ADD CONSTRAINT "GASTO_ITEM_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "GASTO_ITEM" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "GASTO_ITEM" MODIFY ("ID_GASTO" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table INVERSION_ACTIVIDAD
--------------------------------------------------------

  ALTER TABLE "INVERSION_ACTIVIDAD" ADD CONSTRAINT "INVERSION_ACTIVIDAD_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "INVERSION_ACTIVIDAD" MODIFY ("ID_INVERSION" NOT NULL ENABLE);
  ALTER TABLE "INVERSION_ACTIVIDAD" MODIFY ("ID_ACTIVIDAD" NOT NULL ENABLE);
  ALTER TABLE "INVERSION_ACTIVIDAD" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "INVERSION_ACTIVIDAD" ADD CONSTRAINT "INVERSION_ACTIVIDAD_UK1" UNIQUE ("ID_INVERSION", "ID_ACTIVIDAD") ENABLE;
--------------------------------------------------------
--  Constraints for Table MANTENIMIENTO
--------------------------------------------------------

  ALTER TABLE "MANTENIMIENTO" MODIFY ("FORMULA" NOT NULL ENABLE);
  ALTER TABLE "MANTENIMIENTO" MODIFY ("DESCRIPCION" NOT NULL ENABLE);
  ALTER TABLE "MANTENIMIENTO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "MANTENIMIENTO" ADD CONSTRAINT "MANTENIMIENTO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table MARCA
--------------------------------------------------------

  ALTER TABLE "MARCA" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "MARCA" ADD CONSTRAINT "MARCA_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "MARCA" MODIFY ("CODIGO" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table MARCA_TIPO
--------------------------------------------------------

  ALTER TABLE "MARCA_TIPO" ADD CONSTRAINT "MARCA_TIPO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "MARCA_TIPO" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table MOTIVO
--------------------------------------------------------

  ALTER TABLE "MOTIVO" ADD CONSTRAINT "TIPO_SOLICITUD_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "MOTIVO" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table PARAMETRO
--------------------------------------------------------

  ALTER TABLE "PARAMETRO" MODIFY ("MENSAJE" NOT NULL ENABLE);
  ALTER TABLE "PARAMETRO" MODIFY ("VALOR" NOT NULL ENABLE);
  ALTER TABLE "PARAMETRO" MODIFY ("DESCRIPCION" NOT NULL ENABLE);
  ALTER TABLE "PARAMETRO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "PARAMETRO" ADD CONSTRAINT "PARAMETRO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table PERIODO
--------------------------------------------------------

  ALTER TABLE "PERIODO" ADD CONSTRAINT "PERIODO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "PERIODO" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table POLITICA_APROBACION
--------------------------------------------------------

  ALTER TABLE "POLITICA_APROBACION" MODIFY ("ORDEN" NOT NULL ENABLE);
  ALTER TABLE "POLITICA_APROBACION" MODIFY ("TIPO_USUARIO" NOT NULL ENABLE);
  ALTER TABLE "POLITICA_APROBACION" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "POLITICA_APROBACION" ADD CONSTRAINT "POLITICA_APROBACION_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "POLITICA_APROBACION" ADD CONSTRAINT "POLITICA_APROBACION_UK1" UNIQUE ("DESDE", "HASTA", "ORDEN", "TIPO_USUARIO", "ID_TIPO_INSTANCIA_APROBACION") ENABLE;
  ALTER TABLE "POLITICA_APROBACION" MODIFY ("ID_TIPO_INSTANCIA_APROBACION" NOT NULL ENABLE);
  ALTER TABLE "POLITICA_APROBACION" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "POLITICA_APROBACION" MODIFY ("CREATED_AT" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table REPORTE_FORMULA
--------------------------------------------------------

  ALTER TABLE "REPORTE_FORMULA" MODIFY ("FORMULA" NOT NULL ENABLE);
  ALTER TABLE "REPORTE_FORMULA" MODIFY ("QUERY_ID" NOT NULL ENABLE);
  ALTER TABLE "REPORTE_FORMULA" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "REPORTE_FORMULA" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "REPORTE_FORMULA" MODIFY ("DESCRIPCION" NOT NULL ENABLE);
  ALTER TABLE "REPORTE_FORMULA" ADD CONSTRAINT "REPORTE_FORMULA_PK" PRIMARY KEY ("ID_REPORTE") ENABLE;
  ALTER TABLE "REPORTE_FORMULA" MODIFY ("ID_REPORTE" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table REPORTE_QUERY
--------------------------------------------------------

  ALTER TABLE "REPORTE_QUERY" MODIFY ("QUERY" NOT NULL ENABLE);
  ALTER TABLE "REPORTE_QUERY" MODIFY ("NAME" NOT NULL ENABLE);
  ALTER TABLE "REPORTE_QUERY" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "REPORTE_QUERY" ADD CONSTRAINT "REPORTE_QUERY_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table REPORTE_USUARIO
--------------------------------------------------------

  ALTER TABLE "REPORTE_USUARIO" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "REPORTE_USUARIO" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "REPORTE_USUARIO" MODIFY ("ID_USUARIO" NOT NULL ENABLE);
  ALTER TABLE "REPORTE_USUARIO" MODIFY ("ID_REPORTE" NOT NULL ENABLE);
  ALTER TABLE "REPORTE_USUARIO" ADD CONSTRAINT "REPORTE_USUARIO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "REPORTE_USUARIO" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SOLICITUD
--------------------------------------------------------

  ALTER TABLE "SOLICITUD" MODIFY ("ID_DETALLE" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD" ADD CONSTRAINT "SOLICITUD_UK2" UNIQUE ("ID_DETALLE") ENABLE;
  ALTER TABLE "SOLICITUD" ADD CONSTRAINT "SOLICITUD_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SOLICITUD" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD" ADD CONSTRAINT "SOLICITUD_UK1" UNIQUE ("TOKEN") ENABLE;
--------------------------------------------------------
--  Constraints for Table SOLICITUD_CLIENTE
--------------------------------------------------------

  ALTER TABLE "SOLICITUD_CLIENTE" MODIFY ("ID_SOLICITUD" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_CLIENTE" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_CLIENTE" ADD CONSTRAINT "SOLICITUD_CLIENTE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SOLICITUD_CLIENTE" MODIFY ("ID_CLIENTE" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_CLIENTE" MODIFY ("ID_TIPO_CLIENTE" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_CLIENTE" ADD CONSTRAINT "SOLICITUD_CLIENTE_UK1" UNIQUE ("ID_SOLICITUD", "ID_CLIENTE", "ID_TIPO_CLIENTE") ENABLE;
--------------------------------------------------------
--  Constraints for Table SOLICITUD_DETALLE
--------------------------------------------------------

  ALTER TABLE "SOLICITUD_DETALLE" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SOLICITUD_DETALLE" ADD CONSTRAINT "SOLICITUD_DETALLE_UK1" UNIQUE ("ID_DEPOSITO") ENABLE;
--------------------------------------------------------
--  Constraints for Table SOLICITUD_GERENTE
--------------------------------------------------------

  ALTER TABLE "SOLICITUD_GERENTE" ADD CONSTRAINT "SOLICITUD_DERIVADO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SOLICITUD_GERENTE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SOLICITUD_HISTORIAL
--------------------------------------------------------

  ALTER TABLE "SOLICITUD_HISTORIAL" MODIFY ("STATUS_TO" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_HISTORIAL" MODIFY ("USER_FROM" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_HISTORIAL" MODIFY ("USER_TO" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_HISTORIAL" MODIFY ("ID_SOLICITUD" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_HISTORIAL" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_HISTORIAL" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_HISTORIAL" MODIFY ("CREATED_BY" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_HISTORIAL" MODIFY ("UPDATED_BY" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_HISTORIAL" ADD CONSTRAINT "SOLICITUD_HISTORIAL_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SOLICITUD_HISTORIAL" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SOLICITUD_PRODUCTO
--------------------------------------------------------

  ALTER TABLE "SOLICITUD_PRODUCTO" MODIFY ("ID_SOLICITUD" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_PRODUCTO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SOLICITUD_PRODUCTO" ADD CONSTRAINT "SOLICITUD_FAMILIA_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SOLICITUD_PRODUCTO" MODIFY ("ID_PRODUCTO" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SOLICITUD_TIPO
--------------------------------------------------------

  ALTER TABLE "SOLICITUD_TIPO" ADD CONSTRAINT "SOLICITUD_TIPO_UK" UNIQUE ("CODE") ENABLE;
  ALTER TABLE "SOLICITUD_TIPO" ADD CONSTRAINT "SOLICITUD_TIPO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "SOLICITUD_TIPO" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SUB_ESTADO
--------------------------------------------------------

  ALTER TABLE "SUB_ESTADO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "SUB_ESTADO" MODIFY ("ID_ESTADO" NOT NULL ENABLE);
  ALTER TABLE "SUB_ESTADO" ADD CONSTRAINT "ESTADO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table TIEMPO_ESTIMADO_FLUJO
--------------------------------------------------------

  ALTER TABLE "TIEMPO_ESTIMADO_FLUJO" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "TIEMPO_ESTIMADO_FLUJO" ADD CONSTRAINT "TIEMPO_ESTIMADO_FLUJO_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table TIPO_ACTIVIDAD
--------------------------------------------------------

  ALTER TABLE "TIPO_ACTIVIDAD" ADD CONSTRAINT "TIPO_ACTIVIDAD_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "TIPO_ACTIVIDAD" MODIFY ("TIPO_CLIENTE" NOT NULL ENABLE);
  ALTER TABLE "TIPO_ACTIVIDAD" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "TIPO_ACTIVIDAD" MODIFY ("COLOR" NOT NULL ENABLE);
  ALTER TABLE "TIPO_ACTIVIDAD" MODIFY ("NOMBRE" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TIPO_CLIENTE
--------------------------------------------------------

  ALTER TABLE "TIPO_CLIENTE" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "TIPO_CLIENTE" ADD CONSTRAINT "TIPO_CLIENTE_PK" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table TIPO_COMPROBANTE
--------------------------------------------------------

  ALTER TABLE "TIPO_COMPROBANTE" ADD CONSTRAINT "TIPO_COMPROBANTE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "TIPO_COMPROBANTE" MODIFY ("CTA_SUNAT" NOT NULL ENABLE);
  ALTER TABLE "TIPO_COMPROBANTE" MODIFY ("DESCRIPCION" NOT NULL ENABLE);
  ALTER TABLE "TIPO_COMPROBANTE" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "TIPO_COMPROBANTE" MODIFY ("IGV" NOT NULL ENABLE);
  ALTER TABLE "TIPO_COMPROBANTE" MODIFY ("MARCA" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TIPO_DEVOLUCION
--------------------------------------------------------

  ALTER TABLE "TIPO_DEVOLUCION" ADD CONSTRAINT "TIPO_DEVOLUCION_UK1" UNIQUE ("ID", "DESCRIPCION") ENABLE;
  ALTER TABLE "TIPO_DEVOLUCION" ADD CONSTRAINT "TIPO_DEVOLUCION_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "TIPO_DEVOLUCION" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "TIPO_DEVOLUCION" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "TIPO_DEVOLUCION" MODIFY ("DESCRIPCION" NOT NULL ENABLE);
  ALTER TABLE "TIPO_DEVOLUCION" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TIPO_FONDO_SUBCATEGORIA
--------------------------------------------------------

  ALTER TABLE "TIPO_FONDO_SUBCATEGORIA" MODIFY ("RELACION" NOT NULL ENABLE);
  ALTER TABLE "TIPO_FONDO_SUBCATEGORIA" MODIFY ("CODIGO" NOT NULL ENABLE);
  ALTER TABLE "TIPO_FONDO_SUBCATEGORIA" ADD CONSTRAINT "TIPO_FONDO_SUBCATEGORIA_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "TIPO_FONDO_SUBCATEGORIA" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "TIPO_FONDO_SUBCATEGORIA" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "TIPO_FONDO_SUBCATEGORIA" MODIFY ("DESCRIPCION" NOT NULL ENABLE);
  ALTER TABLE "TIPO_FONDO_SUBCATEGORIA" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TIPO_GASTO
--------------------------------------------------------

  ALTER TABLE "TIPO_GASTO" ADD CONSTRAINT "PK_TIPOGASTO" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table TIPO_INSTANCIA_APROBACION
--------------------------------------------------------

  ALTER TABLE "TIPO_INSTANCIA_APROBACION" ADD CONSTRAINT "TIPO_INSTANCIA_APROBACION_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "TIPO_INSTANCIA_APROBACION" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "TIPO_INSTANCIA_APROBACION" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "TIPO_INSTANCIA_APROBACION" MODIFY ("DESCRIPCION" NOT NULL ENABLE);
  ALTER TABLE "TIPO_INSTANCIA_APROBACION" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TIPO_INVERSION
--------------------------------------------------------

  ALTER TABLE "TIPO_INVERSION" ADD CONSTRAINT "TIPO_INVERSION_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "TIPO_INVERSION" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "TIPO_INVERSION" MODIFY ("ID_TIPO_INSTANCIA_APROBACION" NOT NULL ENABLE);
  ALTER TABLE "TIPO_INVERSION" MODIFY ("ID_FONDO_CONTABLE" NOT NULL ENABLE);
  ALTER TABLE "TIPO_INVERSION" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "TIPO_INVERSION" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "TIPO_INVERSION" MODIFY ("NOMBRE" NOT NULL ENABLE);
  ALTER TABLE "TIPO_INVERSION" MODIFY ("CODIGO_ACTIVIDAD" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TIPO_MONEDA
--------------------------------------------------------

  ALTER TABLE "TIPO_MONEDA" ADD CONSTRAINT "PK_TIPO_MONEDA" PRIMARY KEY ("ID") ENABLE;
--------------------------------------------------------
--  Constraints for Table TIPO_PAGO
--------------------------------------------------------

  ALTER TABLE "TIPO_PAGO" ADD CONSTRAINT "TIPO_PAGO_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "TIPO_PAGO" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TIPO_REGIMEN
--------------------------------------------------------

  ALTER TABLE "TIPO_REGIMEN" ADD CONSTRAINT "RETENCION_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "TIPO_REGIMEN" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table USER_TEMPORAL
--------------------------------------------------------

  ALTER TABLE "USER_TEMPORAL" ADD CONSTRAINT "USER_TEMPORAL_UK1" UNIQUE ("DELETED_AT", "ID_USER") ENABLE;
  ALTER TABLE "USER_TEMPORAL" MODIFY ("ID_USER" NOT NULL ENABLE);
  ALTER TABLE "USER_TEMPORAL" MODIFY ("ID_USER_TEMP" NOT NULL ENABLE);
  ALTER TABLE "USER_TEMPORAL" MODIFY ("CREATED_AT" NOT NULL ENABLE);
  ALTER TABLE "USER_TEMPORAL" MODIFY ("UPDATED_AT" NOT NULL ENABLE);
  ALTER TABLE "USER_TEMPORAL" MODIFY ("CREATED_BY" NOT NULL ENABLE);
  ALTER TABLE "USER_TEMPORAL" MODIFY ("UPDATED_BY" NOT NULL ENABLE);
  ALTER TABLE "USER_TEMPORAL" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "USER_TEMPORAL" ADD CONSTRAINT "USER_TEMPORAL_ID_PK" PRIMARY KEY ("ID") ENABLE;
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
--  Ref Constraints for Table DEVOLUCION
--------------------------------------------------------

  ALTER TABLE "DEVOLUCION" ADD CONSTRAINT "DEVOLUCION_FK1" FOREIGN KEY ("ID_SOLICITUD")
	  REFERENCES "SOLICITUD" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "DEVOLUCION" ADD CONSTRAINT "DEVOLUCION_FK2" FOREIGN KEY ("ID_ESTADO_DEVOLUCION")
	  REFERENCES "ESTADO_DEVOLUCION" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "DEVOLUCION" ADD CONSTRAINT "DEVOLUCION_FK3" FOREIGN KEY ("ID_TIPO_DEVOLUCION")
	  REFERENCES "TIPO_DEVOLUCION" ("ID") ON DELETE CASCADE ENABLE;
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
--  Ref Constraints for Table POLITICA_APROBACION
--------------------------------------------------------

  ALTER TABLE "POLITICA_APROBACION" ADD CONSTRAINT "POLITICA_APROBACION_FK1" FOREIGN KEY ("ID_TIPO_INSTANCIA_APROBACION")
	  REFERENCES "TIPO_INSTANCIA_APROBACION" ("ID") ON DELETE CASCADE ENABLE;
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
--------------------------------------------------------
--  Ref Constraints for Table TIPO_INVERSION
--------------------------------------------------------

  ALTER TABLE "TIPO_INVERSION" ADD CONSTRAINT "TIPO_INVERSION_FK1" FOREIGN KEY ("ID_TIPO_INSTANCIA_APROBACION")
	  REFERENCES "TIPO_INSTANCIA_APROBACION" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "TIPO_INVERSION" ADD CONSTRAINT "TIPO_INVERSION_FK2" FOREIGN KEY ("ID_FONDO_CONTABLE")
	  REFERENCES "FONDO_CONTABLE" ("ID") ON DELETE CASCADE ENABLE;
