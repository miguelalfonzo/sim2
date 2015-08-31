--------------------------------------------------------
-- Archivo creado  - lunes-agosto-31-2015   
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
REM INSERTING into FONDO_GERENTE_PRODUCTO
SET DEFINE OFF;
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('573','3','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('574','4','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('576','6','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('577','7','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('578','8','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('579','9','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('581','11','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('582','12','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('583','13','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('584','14','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('585','15','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('586','16','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('588','18','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('589','19','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('590','20','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('591','21','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('592','22','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('593','23','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('595','25','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('596','26','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('597','27','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('598','28','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('599','29','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('600','30','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('602','2','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('603','3','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('604','4','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('606','6','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('607','7','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('608','8','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('609','9','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('610','10','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('611','11','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('612','12','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('613','13','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('614','14','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('615','15','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('616','16','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('617','17','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('618','18','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('619','19','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('620','20','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('621','21','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('622','22','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('623','23','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('624','24','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('625','25','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('626','26','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('627','27','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('628','28','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('629','29','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('630','30','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('631','1','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('632','2','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('633','3','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('634','4','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('635','5','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('636','6','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('637','7','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('638','8','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('639','9','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('640','10','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('641','11','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('642','12','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('643','13','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('644','14','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('645','15','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('646','16','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('647','17','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('648','18','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('649','19','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('650','20','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('651','21','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('652','22','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('653','23','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('654','24','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('655','25','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('656','26','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('657','27','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('658','28','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('659','29','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('660','30','96',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('661','1','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('662','2','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('663','3','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('664','4','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('665','5','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('666','6','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('667','7','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('668','8','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('669','9','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('670','10','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('671','11','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('672','12','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('673','13','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('674','14','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('675','15','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('676','16','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('677','17','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('678','18','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('679','19','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('680','20','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('681','21','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('682','22','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('683','23','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('684','24','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('685','25','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('686','26','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('687','27','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('688','28','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('689','29','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('690','30','95',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('691','1','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('692','2','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('693','3','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('694','4','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('695','5','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('696','6','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('697','7','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('698','8','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('699','9','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('700','10','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('701','11','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('702','12','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('703','13','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('704','14','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('705','15','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('706','16','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('707','17','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('708','18','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('709','19','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('710','20','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('711','21','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('712','22','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('713','23','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('714','24','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('715','25','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('716','26','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('717','27','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('718','28','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('719','29','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('720','30','18',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('721','1','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('722','2','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('723','3','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('724','4','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('725','5','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('726','6','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('727','7','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('728','8','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('729','9','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('730','10','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('731','11','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('732','12','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('733','13','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('734','14','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('735','15','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('736','16','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('737','17','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('738','18','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('739','19','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('740','20','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('741','21','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('742','22','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('743','23','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('744','24','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('745','25','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('746','26','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('747','27','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('748','28','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('749','29','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('750','30','23',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('751','1','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('752','2','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('753','3','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('754','4','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('755','5','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('756','6','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('757','7','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('758','8','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('759','9','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('760','10','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('761','11','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('762','12','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('763','13','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('764','14','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('765','15','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('766','16','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('767','17','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('768','18','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('769','19','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('770','20','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('771','21','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('772','22','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('773','23','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('774','24','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('775','25','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('776','26','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('777','27','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('778','28','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('779','29','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('780','30','49',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('781','1','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('782','2','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('783','3','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('784','4','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('785','5','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('786','6','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('787','7','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('788','8','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('789','9','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('790','10','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('791','11','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('792','12','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('793','13','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('794','14','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('795','15','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('796','16','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('797','17','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('798','18','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('799','19','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('800','20','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('801','21','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('802','22','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('803','23','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('804','24','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('805','25','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('806','26','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('807','27','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('808','28','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('809','29','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('810','30','92',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('811','1','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('812','2','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('813','3','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('814','4','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('815','5','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('816','6','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('817','7','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('818','8','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('819','9','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('820','10','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('821','11','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('822','12','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('823','13','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('824','14','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('825','15','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('826','16','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('827','17','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('828','18','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('829','19','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('830','20','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('831','21','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('832','22','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('833','23','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('834','24','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('835','25','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('836','26','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('837','27','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('838','28','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('839','29','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('840','30','29',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('841','1','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('842','2','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('843','3','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('844','4','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('845','5','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1113','3','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1114','4','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1115','5','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1116','6','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1117','7','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1118','8','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1119','9','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1120','10','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1121','11','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1122','12','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1123','13','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1124','14','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1125','15','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1126','16','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1127','17','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1128','18','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1129','19','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1130','20','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1131','21','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1132','22','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1133','23','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1134','24','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1135','25','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1136','26','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1137','27','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1138','28','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1139','29','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1140','30','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1141','1','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1143','3','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1144','4','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1146','6','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1147','7','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1148','8','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1149','9','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1151','11','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1152','12','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1154','14','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1155','15','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1156','16','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1157','17','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1158','18','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1159','19','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1160','20','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1161','21','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1163','23','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1164','24','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1165','25','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1166','26','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1167','27','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1168','28','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1170','30','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1171','1','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1172','2','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1174','4','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1175','5','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1176','6','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1177','7','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1178','8','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1179','9','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1180','10','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1181','11','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1182','12','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1183','13','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1184','14','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1186','16','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1187','17','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1188','18','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1189','19','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1191','21','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1192','22','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1193','23','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1194','24','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1195','25','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1197','27','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1198','28','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1199','29','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1200','30','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1201','1','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1202','2','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1203','3','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1204','4','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1205','5','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1206','6','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1207','7','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1208','8','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1209','9','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1210','10','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1211','11','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1212','12','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1213','13','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1214','14','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1216','16','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1217','17','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1218','18','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1219','19','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1220','20','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1221','21','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1222','22','97',null,to_date('23/07/15 09:45:09','DD/MM/RR HH24:MI:SS'),null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1223','23','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1224','24','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1225','25','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1226','26','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1227','27','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1228','28','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1229','29','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1230','30','97',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1231','1','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1232','2','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1233','3','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1234','4','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1235','5','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1236','6','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1237','7','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1238','8','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1239','9','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1240','10','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1241','11','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1242','12','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1243','13','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1244','14','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1245','15','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1246','16','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1247','17','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1248','18','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1249','19','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1250','20','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1251','21','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1252','22','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1253','23','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1254','24','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1255','25','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1256','26','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1257','27','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1258','28','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1259','29','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1260','30','35',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1261','1','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1262','2','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1263','3','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1264','4','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1265','5','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1266','6','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1267','7','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1268','8','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1269','9','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1270','10','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1271','11','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1272','12','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1273','13','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1274','14','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1275','15','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1276','16','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1277','17','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1278','18','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1279','19','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1280','20','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1281','21','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1282','22','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1283','23','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1284','24','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1285','25','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1286','26','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1287','27','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1288','28','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1289','29','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1290','30','83',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1291','1','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1292','2','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1293','3','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1294','4','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1295','5','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1296','6','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1297','7','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1298','8','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1299','9','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1300','10','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1301','11','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1302','12','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1303','13','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1304','14','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1305','15','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1306','16','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1307','17','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1308','18','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1309','19','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1310','20','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1311','21','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1312','22','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1313','23','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1314','24','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1315','25','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1316','26','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1317','27','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1318','28','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1319','29','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1320','30','50',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1321','1','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1322','2','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1323','3','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1324','4','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1325','5','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1326','6','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1327','7','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1328','8','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1329','9','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1330','10','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1331','11','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1332','12','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1333','13','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1334','14','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1335','15','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1336','16','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1337','17','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1338','18','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1339','19','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1340','20','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1341','21','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1342','22','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1343','23','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1344','24','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1345','25','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1346','26','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1347','27','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1348','28','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1349','29','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1350','30','47',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1351','1','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1352','2','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1353','3','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1354','4','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1355','5','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1356','6','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1357','7','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1358','8','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1359','9','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1360','10','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1361','11','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1362','12','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1363','13','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1364','14','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1365','15','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1366','16','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1367','17','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1368','18','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1369','19','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1370','20','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1371','21','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1372','22','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1373','23','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1374','24','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1375','25','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1376','26','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1377','27','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1378','28','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1379','29','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1380','30','76',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1381','1','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1382','2','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1383','3','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1384','4','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1385','5','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1386','6','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1387','7','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1388','8','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1389','9','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1390','10','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1391','11','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1392','12','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1393','13','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1394','14','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1395','15','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1396','16','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1397','17','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1398','18','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1399','19','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1400','20','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1401','21','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1402','22','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1403','23','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1404','24','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1405','25','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1406','26','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1408','28','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1409','29','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1410','30','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1411','1','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1412','2','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1413','3','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1414','4','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1416','6','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1417','7','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1418','8','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1420','10','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1421','11','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1422','12','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1423','13','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1424','14','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1425','15','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1426','16','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1428','18','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1429','19','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1430','20','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1431','21','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1432','22','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1433','23','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1435','25','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1436','26','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1437','27','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1438','28','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1439','29','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1440','30','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1442','2','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1443','3','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1444','4','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1445','5','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1447','7','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1448','8','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1449','9','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1450','10','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1451','11','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1453','13','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1454','14','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1455','15','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1456','16','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1457','17','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1458','18','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1460','20','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1461','21','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1462','22','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1463','23','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1464','24','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1465','25','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1467','27','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1468','28','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1469','29','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1470','30','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1471','1','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1472','2','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1474','4','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1475','5','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1476','6','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1477','7','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1478','8','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1480','10','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1481','11','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1482','12','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1483','13','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1484','14','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1485','15','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1486','16','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1488','18','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1489','19','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1490','20','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1491','21','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1492','22','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1493','23','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1495','25','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1496','26','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1497','27','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1498','28','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1','1','79',null,to_date('27/08/15 17:42:58','DD/MM/RR HH24:MI:SS'),null,null,'42',null,'1900','230,3');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('2','2','79',null,to_date('23/07/15 16:24:28','DD/MM/RR HH24:MI:SS'),null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('3','3','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('4','4','79',null,to_date('24/07/15 09:25:33','DD/MM/RR HH24:MI:SS'),null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('5','5','79',null,to_date('14/08/15 16:24:36','DD/MM/RR HH24:MI:SS'),null,null,'42',null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('6','6','79',null,to_date('28/08/15 16:49:00','DD/MM/RR HH24:MI:SS'),null,null,'42',null,'1800','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('7','7','79',null,to_date('28/08/15 10:23:11','DD/MM/RR HH24:MI:SS'),null,null,'42',null,'1990','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('8','8','79',null,to_date('08/07/15 17:06:13','DD/MM/RR HH24:MI:SS'),null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('9','9','79',null,to_date('22/07/15 10:57:01','DD/MM/RR HH24:MI:SS'),null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('10','10','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('11','11','79',null,to_date('24/07/15 10:27:26','DD/MM/RR HH24:MI:SS'),null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('12','12','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('13','13','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('14','14','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('15','15','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('16','16','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('17','17','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('18','18','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('19','19','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('20','20','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('21','21','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('22','22','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('23','23','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('24','24','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('25','25','79',null,to_date('24/08/15 11:47:53','DD/MM/RR HH24:MI:SS'),null,null,'11',null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('26','26','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('27','27','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('28','28','79',null,to_date('24/08/15 17:26:47','DD/MM/RR HH24:MI:SS'),null,null,'42',null,'1700','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('29','29','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('30','30','79',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('31','1','73',null,to_date('27/08/15 17:42:58','DD/MM/RR HH24:MI:SS'),null,null,'42',null,'1850','345,45');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('32','2','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('33','3','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('34','4','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('35','5','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('36','6','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('37','7','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('38','8','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('39','9','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('40','10','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('41','11','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('42','12','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('43','13','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('44','14','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('45','15','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('46','16','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('47','17','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('48','18','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('49','19','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('50','20','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('51','21','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('52','22','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('53','23','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('54','24','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('55','25','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('56','26','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('57','27','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('58','28','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('59','29','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('60','30','73',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('61','1','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('62','2','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('63','3','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('64','4','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('65','5','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('66','6','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('67','7','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('68','8','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('69','9','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('70','10','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('71','11','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('72','12','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('73','13','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('74','14','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('75','15','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('76','16','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('77','17','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('78','18','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('79','19','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('80','20','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('81','21','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('82','22','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('83','23','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('84','24','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('85','25','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('86','26','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('87','27','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('88','28','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('89','29','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('90','30','94',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('91','1','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('92','2','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('93','3','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('94','4','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('95','5','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('96','6','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('97','7','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('98','8','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('99','9','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('100','10','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('101','11','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('102','12','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('103','13','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('104','14','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('105','15','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('106','16','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('107','17','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('108','18','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('109','19','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('110','20','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('111','21','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('112','22','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('113','23','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('114','24','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('115','25','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('116','26','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('117','27','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('118','28','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('119','29','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('120','30','16',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('121','1','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('122','2','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('123','3','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('124','4','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('125','5','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('126','6','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('127','7','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('128','8','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('129','9','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('130','10','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('131','11','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('132','12','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('133','13','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('134','14','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('135','15','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('136','16','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('137','17','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('138','18','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('139','19','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('140','20','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('141','21','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('142','22','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('143','23','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('144','24','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('145','25','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('146','26','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('147','27','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('148','28','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('149','29','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('150','30','101',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('151','1','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('152','2','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('153','3','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('154','4','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('155','5','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('156','6','19',null,to_date('09/07/15 11:08:07','DD/MM/RR HH24:MI:SS'),null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('157','7','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('158','8','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('159','9','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('160','10','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('846','6','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('847','7','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('848','8','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('849','9','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('850','10','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('851','11','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('853','13','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('854','14','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('855','15','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('856','16','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('857','17','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('858','18','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('859','19','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('860','20','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('861','21','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('862','22','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('864','24','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('865','25','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('866','26','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('867','27','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('868','28','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('869','29','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('871','1','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('872','2','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('873','3','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('874','4','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('876','6','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('877','7','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('878','8','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('879','9','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('881','11','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('882','12','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('883','13','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('885','15','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('886','16','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('887','17','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('888','18','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('889','19','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('891','21','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('892','22','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('893','23','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('894','24','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('895','25','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('896','26','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('898','28','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('899','29','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('900','30','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('901','1','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('902','2','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('904','4','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('905','5','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('906','6','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('907','7','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('908','8','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('909','9','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('910','10','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('911','11','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('912','12','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('913','13','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('914','14','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('915','15','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('916','16','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('917','17','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('918','18','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('919','19','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('920','20','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('921','21','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('922','22','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('923','23','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('924','24','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('925','25','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('926','26','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('927','27','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('928','28','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('929','29','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('930','30','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('931','1','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('932','2','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('933','3','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('934','4','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('935','5','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('936','6','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('937','7','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('938','8','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('939','9','9',null,to_date('09/07/15 11:00:38','DD/MM/RR HH24:MI:SS'),null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('940','10','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('941','11','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('942','12','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('943','13','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('944','14','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('945','15','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('946','16','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('947','17','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('948','18','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('949','19','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('950','20','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('951','21','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('952','22','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('953','23','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('954','24','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('955','25','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('956','26','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('957','27','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('958','28','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('959','29','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('960','30','9',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('961','1','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('962','2','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('963','3','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('964','4','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('965','5','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('966','6','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('967','7','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('968','8','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('969','9','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('970','10','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('971','11','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('972','12','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('973','13','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('974','14','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('975','15','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('976','16','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('977','17','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('978','18','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('979','19','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('980','20','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('981','21','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('982','22','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('983','23','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('984','24','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('985','25','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('986','26','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('987','27','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('988','28','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('989','29','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('990','30','102',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('991','1','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('992','2','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('993','3','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('994','4','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('995','5','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('996','6','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('997','7','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('998','8','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('999','9','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1000','10','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1001','11','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1002','12','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1003','13','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1004','14','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1005','15','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1006','16','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1007','17','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1008','18','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1009','19','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1010','20','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1011','21','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1012','22','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1013','23','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1014','24','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1015','25','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1016','26','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1017','27','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1018','28','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1019','29','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1020','30','105',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1021','1','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1022','2','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1023','3','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1024','4','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1025','5','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1026','6','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1027','7','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1028','8','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1029','9','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1030','10','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1031','11','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1032','12','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1033','13','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1034','14','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1035','15','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1036','16','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1037','17','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1038','18','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1039','19','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1040','20','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1041','21','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1042','22','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1043','23','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1044','24','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1045','25','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1046','26','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1047','27','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1048','28','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1049','29','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1050','30','38',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1051','1','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1052','2','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1053','3','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1054','4','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1055','5','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1056','6','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1057','7','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1058','8','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1059','9','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1060','10','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1061','11','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1062','12','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1063','13','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1064','14','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1065','15','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1066','16','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1067','17','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1068','18','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1069','19','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1070','20','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1071','21','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1072','22','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1073','23','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1074','24','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1075','25','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1076','26','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1077','27','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1078','28','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1079','29','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1080','30','14',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1081','1','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1082','2','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1083','3','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1084','4','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1085','5','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1086','6','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1087','7','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1088','8','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1089','9','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1090','10','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1091','11','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1092','12','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1093','13','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1094','14','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1095','15','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1096','16','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1097','17','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1098','18','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1099','19','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1100','20','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1101','21','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1102','22','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1103','23','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1104','24','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1105','25','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1106','26','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1107','27','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1108','28','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1109','29','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1110','30','93',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1111','1','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1112','2','85',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('304','4','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('305','5','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('306','6','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('307','7','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('308','8','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('309','9','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('310','10','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('311','11','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('312','12','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('313','13','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('314','14','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('315','15','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('316','16','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('317','17','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('318','18','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('319','19','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('320','20','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('321','21','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('322','22','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('323','23','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('324','24','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('325','25','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('326','26','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('327','27','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('328','28','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('329','29','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('330','30','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('331','1','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('332','2','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('333','3','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('334','4','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('335','5','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('336','6','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('337','7','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('338','8','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('339','9','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('340','10','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('341','11','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('342','12','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('343','13','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('344','14','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('345','15','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('346','16','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('347','17','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('348','18','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('349','19','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('350','20','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('351','21','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('352','22','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('353','23','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('354','24','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('355','25','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('356','26','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('357','27','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('358','28','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('359','29','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('360','30','91',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('361','1','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('362','2','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('363','3','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('364','4','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('365','5','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('366','6','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('367','7','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('368','8','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('369','9','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('370','10','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('371','11','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('372','12','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('373','13','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('374','14','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('375','15','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('376','16','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('377','17','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('378','18','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('379','19','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('380','20','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('381','21','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('382','22','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('383','23','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('384','24','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('385','25','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('386','26','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('387','27','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('388','28','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('389','29','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('390','30','80',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('391','1','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('392','2','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('393','3','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('394','4','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('395','5','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('396','6','81',null,to_date('08/07/15 18:18:49','DD/MM/RR HH24:MI:SS'),null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('397','7','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('398','8','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('399','9','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('400','10','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('401','11','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('402','12','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('403','13','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('404','14','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('405','15','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('406','16','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('407','17','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('408','18','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('409','19','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('410','20','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('411','21','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('412','22','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('413','23','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('414','24','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('415','25','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('416','26','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('417','27','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('418','28','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('419','29','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('420','30','81',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('421','1','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('422','2','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('423','3','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('424','4','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('425','5','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('426','6','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('427','7','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('428','8','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('429','9','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('430','10','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('431','11','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('432','12','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('433','13','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('434','14','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('435','15','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('436','16','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('437','17','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('438','18','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('439','19','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('440','20','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('441','21','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('442','22','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('443','23','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('444','24','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('445','25','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('446','26','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('447','27','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('448','28','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('449','29','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('450','30','88',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('451','1','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('452','2','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('453','3','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('454','4','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('455','5','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('456','6','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('457','7','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('458','8','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('459','9','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('460','10','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('461','11','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('462','12','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('463','13','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('464','14','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('465','15','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('466','16','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('467','17','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('468','18','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('469','19','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('470','20','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('471','21','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('472','22','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('473','23','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('474','24','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('475','25','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('476','26','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('477','27','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('478','28','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('479','29','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('480','30','2',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('481','1','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('482','2','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('483','3','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('484','4','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('485','5','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('486','6','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('487','7','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('488','8','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('489','9','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('490','10','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('491','11','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('492','12','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('493','13','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('494','14','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('495','15','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('496','16','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('497','17','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('498','18','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('499','19','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('500','20','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('501','21','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('502','22','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('503','23','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('504','24','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('505','25','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('506','26','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('507','27','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('508','28','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('509','29','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('510','30','26',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('511','1','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('512','2','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('513','3','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('514','4','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('515','5','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('516','6','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('517','7','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('518','8','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('519','9','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('520','10','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('521','11','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('522','12','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('523','13','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('524','14','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('525','15','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('526','16','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('527','17','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('528','18','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('529','19','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('530','20','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('531','21','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('532','22','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('533','23','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('534','24','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('535','25','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('536','26','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('537','27','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('538','28','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('539','29','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('540','30','8',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('541','1','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('542','2','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('543','3','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('544','4','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('545','5','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('546','6','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('547','7','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('548','8','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('549','9','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('550','10','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('551','11','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('552','12','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('553','13','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('554','14','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('555','15','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('556','16','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('557','17','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('558','18','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('559','19','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('560','20','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('561','21','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('562','22','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('563','23','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('564','24','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('565','25','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('566','26','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('567','27','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('568','28','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('569','29','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('570','30','1',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('571','1','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('572','2','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1632','12','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1637','17','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1641','21','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1646','26','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1650','30','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('161','11','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('162','12','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('163','13','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('164','14','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('165','15','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('166','16','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('167','17','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('168','18','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('169','19','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('170','20','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('171','21','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('172','22','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('173','23','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('174','24','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('175','25','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('176','26','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('177','27','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('178','28','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('179','29','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('180','30','19',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('181','1','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('182','2','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('183','3','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('184','4','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('185','5','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('186','6','4',null,to_date('07/07/15 11:21:15','DD/MM/RR HH24:MI:SS'),null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('187','7','4',null,to_date('16/07/15 10:20:24','DD/MM/RR HH24:MI:SS'),null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('188','8','4',null,to_date('08/07/15 17:06:13','DD/MM/RR HH24:MI:SS'),null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('189','9','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('190','10','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('191','11','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('192','12','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('193','13','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('194','14','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('195','15','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('196','16','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('197','17','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('198','18','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('199','19','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('200','20','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('201','21','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('202','22','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('203','23','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('204','24','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('205','25','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('206','26','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('207','27','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('208','28','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('209','29','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('210','30','4',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('211','1','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('212','2','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('213','3','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('214','4','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('216','6','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('217','7','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('218','8','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('220','10','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('221','11','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('223','13','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('224','14','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('225','15','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('226','16','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('227','17','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('228','18','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('229','19','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('230','20','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('232','22','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('233','23','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('234','24','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('235','25','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('236','26','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('237','27','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('239','29','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('240','30','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('241','1','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('242','2','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('243','3','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('244','4','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('246','6','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('247','7','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('248','8','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('249','9','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('250','10','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('252','12','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('253','13','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('254','14','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('255','15','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('256','16','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('257','17','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('258','18','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('260','20','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('261','21','21',null,to_date('07/07/15 11:21:15','DD/MM/RR HH24:MI:SS'),null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('262','22','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('263','23','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('264','24','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('266','26','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('267','27','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('268','28','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('269','29','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('270','30','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('271','1','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('273','3','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('274','4','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('276','6','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('277','7','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('278','8','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('279','9','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('281','11','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('282','12','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('283','13','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('285','15','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('286','16','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('287','17','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('288','18','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('289','19','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('290','20','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('292','22','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('293','23','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('294','24','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('295','25','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('296','26','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('297','27','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('299','29','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('300','30','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('301','1','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('302','2','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('303','3','22',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1499','29','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1500','30','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1501','1','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1502','2','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1503','3','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1504','4','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1505','5','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1506','6','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1507','7','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1508','8','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1509','9','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1510','10','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1511','11','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1512','12','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1513','13','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1514','14','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1515','15','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1516','16','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1517','17','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1518','18','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1519','19','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1520','20','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1521','21','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1522','22','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1523','23','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1524','24','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1525','25','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1526','26','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1527','27','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1528','28','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1529','29','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1530','30','59',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1531','1','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1532','2','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1533','3','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1534','4','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1535','5','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1536','6','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1537','7','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1538','8','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1539','9','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1540','10','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1541','11','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1542','12','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1543','13','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1544','14','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1545','15','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1546','16','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1547','17','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1548','18','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1549','19','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1550','20','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1551','21','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1552','22','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1553','23','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1554','24','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1555','25','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1556','26','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1557','27','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1558','28','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1559','29','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1560','30','61',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1561','1','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1562','2','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1563','3','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1564','4','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1565','5','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1566','6','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1567','7','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1568','8','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1569','9','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1570','10','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1571','11','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1572','12','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1573','13','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1574','14','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1575','15','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1576','16','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1577','17','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1578','18','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1579','19','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1580','20','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1581','21','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1582','22','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1583','23','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1584','24','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1585','25','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1586','26','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1587','27','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1588','28','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1589','29','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1590','30','66',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1591','1','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1592','2','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1593','3','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1594','4','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1595','5','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1596','6','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1597','7','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1598','8','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1599','9','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1600','10','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1601','11','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1602','12','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1603','13','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1604','14','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1605','15','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1606','16','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1607','17','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1608','18','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1609','19','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1610','20','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1611','21','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1612','22','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1613','23','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1614','24','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1615','25','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1616','26','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1617','27','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1618','28','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1619','29','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1620','30','54',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1621','1','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1622','2','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1623','3','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1624','4','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1625','5','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1626','6','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1627','7','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1628','8','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1629','9','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1630','10','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1631','11','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1633','13','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1634','14','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1635','15','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1636','16','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1638','18','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1639','19','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1640','20','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1642','22','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1643','23','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1644','24','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1645','25','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1647','27','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1648','28','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1649','29','72',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1651','1','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1652','2','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1653','3','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1654','4','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1655','5','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1656','6','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1657','7','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1658','8','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1659','9','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1660','10','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1661','11','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1662','12','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1663','13','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1664','14','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1665','15','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1666','16','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1667','17','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1668','18','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1669','19','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1670','20','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1671','21','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1672','22','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1673','23','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1674','24','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1675','25','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1676','26','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1677','27','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1678','28','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1679','29','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1680','30','69',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1681','1','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1682','2','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1683','3','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1684','4','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1685','5','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1686','6','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1687','7','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1688','8','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1689','9','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1690','10','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1691','11','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1692','12','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1693','13','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1694','14','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1695','15','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1696','16','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1697','17','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1698','18','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1699','19','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1700','20','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1701','21','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1702','22','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1703','23','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1704','24','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1705','25','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1706','26','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1707','27','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1708','28','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1709','29','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1710','30','70',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1215','15','97',null,to_date('04/08/15 15:49:55','DD/MM/RR HH24:MI:SS'),null,null,'42',null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('575','5','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('580','10','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('587','17','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('594','24','30',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('601','1','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('605','5','90',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('852','12','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('863','23','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('870','30','12',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('875','5','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('880','10','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('884','14','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('890','20','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('897','27','27',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('903','3','33',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1142','2','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1145','5','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1150','10','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1153','13','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1162','22','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1169','29','78',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1173','3','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1185','15','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1190','20','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1196','26','37',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1407','27','106',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1415','5','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1419','9','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1427','17','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1434','24','52',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1441','1','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1446','6','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1452','12','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1459','19','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1466','26','53',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1473','3','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1479','9','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1487','17','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('1494','24','55',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('215','5','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('219','9','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('222','12','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('231','21','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('238','28','77',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('245','5','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('251','11','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('259','19','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('265','25','21',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('272','2','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('275','5','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('280','10','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('284','14','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('291','21','75',null,null,null,null,null,null,'2000','0');
Insert into FONDO_GERENTE_PRODUCTO (ID,SUBCATEGORIA_ID,MARCA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,SALDO,RETENCION) values ('298','28','75',null,null,null,null,null,null,'2000','0');
REM INSERTING into FONDO_INSTITUCION
SET DEFINE OFF;
Insert into FONDO_INSTITUCION (ID,SALDO,SUBCATEGORIA_ID,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,RETENCION) values ('1','40000','12',null,to_date('20/08/15 11:02:29','DD/MM/RR HH24:MI:SS'),null,null,'42',null,'0');
REM INSERTING into FONDO_MARKETING_HISTORIA
SET DEFINE OFF;
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('7','2','28','2000','2',to_date('24/08/15 17:25:03','DD/MM/RR HH24:MI:SS'),to_date('24/08/15 17:25:03','DD/MM/RR HH24:MI:SS'),null,'199','199',null,'2000','P','0','300');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('8','2','28','2000','4',to_date('24/08/15 17:26:47','DD/MM/RR HH24:MI:SS'),to_date('24/08/15 17:26:47','DD/MM/RR HH24:MI:SS'),null,'42','42',null,'1700','P','300','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('21','3','54','150','7',to_date('27/08/15 12:27:11','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 12:27:11','DD/MM/RR HH24:MI:SS'),null,'42','42',null,'250','S','0','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('43','7','1','453,57','3',to_date('27/08/15 17:40:55','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 17:40:55','DD/MM/RR HH24:MI:SS'),null,'11','11',null,'453,57','S','330,3','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('44','7','1','2000','2',to_date('27/08/15 17:40:55','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 17:40:55','DD/MM/RR HH24:MI:SS'),null,'11','11',null,'2000','P','0','330,3');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('45','7','2','696,43','3',to_date('27/08/15 17:40:55','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 17:40:55','DD/MM/RR HH24:MI:SS'),null,'11','11',null,'696,43','S','495,45','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('46','7','31','2000','2',to_date('27/08/15 17:40:55','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 17:40:55','DD/MM/RR HH24:MI:SS'),null,'11','11',null,'2000','P','0','495,45');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('50','7','7','2000','4',to_date('28/08/15 10:23:11','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 10:23:11','DD/MM/RR HH24:MI:SS'),null,'42','42',null,'1990','P','10','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('51','8','51','1000','2',to_date('28/08/15 14:53:28','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 14:53:28','DD/MM/RR HH24:MI:SS'),null,'40','40',null,'1000','S','0','100');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('53','9','1','453,57','4',to_date('28/08/15 15:31:38','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 15:31:38','DD/MM/RR HH24:MI:SS'),null,'42','42',null,'355,29','S','98,28','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('57','10','6','2000','3',to_date('28/08/15 16:45:51','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 16:45:51','DD/MM/RR HH24:MI:SS'),null,'199','199',null,'2000','P','2000','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('58','10','6','2000','2',to_date('28/08/15 16:45:51','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 16:45:51','DD/MM/RR HH24:MI:SS'),null,'199','199',null,'2000','P','0','2000');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('59','10','6','2000','4',to_date('28/08/15 16:46:48','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 16:46:48','DD/MM/RR HH24:MI:SS'),null,'42','42',null,'0','P','2000','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('60','10','6','0','7',to_date('28/08/15 16:49:00','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 16:49:00','DD/MM/RR HH24:MI:SS'),null,'42','42',null,'1800','P','0','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('61','11','40','1000','2',to_date('28/08/15 17:49:48','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 17:49:48','DD/MM/RR HH24:MI:SS'),null,'40','40',null,'1000','S','0','300');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('2','1','7','1000','3',to_date('24/08/15 12:22:53','DD/MM/RR HH24:MI:SS'),to_date('24/08/15 12:22:53','DD/MM/RR HH24:MI:SS'),null,'55','55',null,'1000','S','200','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('3','1','7','1000','2',to_date('24/08/15 12:22:53','DD/MM/RR HH24:MI:SS'),to_date('24/08/15 12:22:53','DD/MM/RR HH24:MI:SS'),null,'55','55',null,'1000','S','0','200');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('6','2','28','2000','3',to_date('24/08/15 17:25:03','DD/MM/RR HH24:MI:SS'),to_date('24/08/15 17:25:03','DD/MM/RR HH24:MI:SS'),null,'199','199',null,'2000','P','300','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('14','3','54','100','5',to_date('26/08/15 15:02:12','DD/MM/RR HH24:MI:SS'),to_date('26/08/15 15:02:12','DD/MM/RR HH24:MI:SS'),null,'43','43',null,'150','S','0','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('22','6','1','1000','2',to_date('27/08/15 14:36:50','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 14:36:50','DD/MM/RR HH24:MI:SS'),null,'40','40',null,'1000','S','0','900');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('23','6','2','1000','2',to_date('27/08/15 14:36:50','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 14:36:50','DD/MM/RR HH24:MI:SS'),null,'40','40',null,'1000','S','0','500');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('24','6','1','1000','3',to_date('27/08/15 14:37:48','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 14:37:48','DD/MM/RR HH24:MI:SS'),null,'55','55',null,'1000','S','900','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('25','6','1','1000','2',to_date('27/08/15 14:37:48','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 14:37:48','DD/MM/RR HH24:MI:SS'),null,'55','55',null,'1000','S','0','900');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('26','6','2','1000','3',to_date('27/08/15 14:37:48','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 14:37:48','DD/MM/RR HH24:MI:SS'),null,'55','55',null,'1000','S','500','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('27','6','2','1000','2',to_date('27/08/15 14:37:48','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 14:37:48','DD/MM/RR HH24:MI:SS'),null,'55','55',null,'1000','S','0','500');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('28','6','2','1000','3',to_date('27/08/15 14:38:15','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 14:38:15','DD/MM/RR HH24:MI:SS'),null,'199','199',null,'1000','S','500','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('5','2','28','2000','2',to_date('24/08/15 17:24:29','DD/MM/RR HH24:MI:SS'),to_date('24/08/15 17:24:29','DD/MM/RR HH24:MI:SS'),null,'11','11',null,'2000','P','0','300');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('29','6','2','1000','2',to_date('27/08/15 14:38:15','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 14:38:15','DD/MM/RR HH24:MI:SS'),null,'199','199',null,'1000','S','0','500');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('30','6','1','1000','3',to_date('27/08/15 14:38:15','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 14:38:15','DD/MM/RR HH24:MI:SS'),null,'199','199',null,'1000','S','900','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('31','6','1','1000','2',to_date('27/08/15 14:38:15','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 14:38:15','DD/MM/RR HH24:MI:SS'),null,'199','199',null,'1000','S','0','900');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('34','6','1','100','7',to_date('27/08/15 14:50:40','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 14:50:40','DD/MM/RR HH24:MI:SS'),null,'42','42',null,'357,14','S','0','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('35','6','2','500','7',to_date('27/08/15 14:50:40','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 14:50:40','DD/MM/RR HH24:MI:SS'),null,'42','42',null,'642,86','S','0','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('36','6','1','357,14','7',to_date('27/08/15 15:59:06','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 15:59:06','DD/MM/RR HH24:MI:SS'),null,'42','42',null,'421,43','S','0','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('37','6','2','642,86','7',to_date('27/08/15 15:59:06','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 15:59:06','DD/MM/RR HH24:MI:SS'),null,'42','42',null,'678,57','S','0','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('40','5','38','550','8',to_date('27/08/15 17:05:26','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 17:05:26','DD/MM/RR HH24:MI:SS'),null,'19','19',null,'1000','S','0','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('48','7','7','2000','3',to_date('28/08/15 10:22:44','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 10:22:44','DD/MM/RR HH24:MI:SS'),null,'199','199',null,'2000','P','10','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('49','7','7','2000','2',to_date('28/08/15 10:22:44','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 10:22:44','DD/MM/RR HH24:MI:SS'),null,'199','199',null,'2000','P','0','10');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('56','10','6','2000','2',to_date('28/08/15 16:45:17','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 16:45:17','DD/MM/RR HH24:MI:SS'),null,'11','11',null,'2000','P','0','2000');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('13','3','54','0','7',to_date('26/08/15 10:51:12','DD/MM/RR HH24:MI:SS'),to_date('26/08/15 10:51:12','DD/MM/RR HH24:MI:SS'),null,'42','42',null,'100','S','0','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('20','5','38','1000','4',to_date('27/08/15 11:10:42','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 11:10:42','DD/MM/RR HH24:MI:SS'),null,'20','20',null,'550','S','450','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('32','6','1','1000','4',to_date('27/08/15 14:40:56','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 14:40:56','DD/MM/RR HH24:MI:SS'),null,'42','42',null,'100','S','900','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('33','6','2','1000','4',to_date('27/08/15 14:40:56','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 14:40:56','DD/MM/RR HH24:MI:SS'),null,'42','42',null,'500','S','500','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('47','7','7','2000','2',to_date('28/08/15 10:22:26','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 10:22:26','DD/MM/RR HH24:MI:SS'),null,'11','11',null,'2000','P','0','10');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('54','8','51','1000','4',to_date('28/08/15 15:33:21','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 15:33:21','DD/MM/RR HH24:MI:SS'),null,'42','42',null,'900','S','100','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('65','11','40','700','8',to_date('28/08/15 18:01:34','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 18:01:34','DD/MM/RR HH24:MI:SS'),null,'19','19',null,'1000','S','0','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('10','3','54','1000','3',to_date('25/08/15 16:04:57','DD/MM/RR HH24:MI:SS'),to_date('25/08/15 16:04:57','DD/MM/RR HH24:MI:SS'),null,'11','11',null,'1000','S','1000','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('11','3','54','1000','2',to_date('25/08/15 16:04:57','DD/MM/RR HH24:MI:SS'),to_date('25/08/15 16:04:57','DD/MM/RR HH24:MI:SS'),null,'11','11',null,'1000','S','0','1000');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('19','5','38','1000','2',to_date('27/08/15 11:09:24','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 11:09:24','DD/MM/RR HH24:MI:SS'),null,'199','199',null,'1000','S','0','450');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('38','6','1','421,43','5',to_date('27/08/15 16:06:13','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 16:06:13','DD/MM/RR HH24:MI:SS'),null,'43','43',null,'453,57','S','0','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('39','6','2','678,57','5',to_date('27/08/15 16:06:13','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 16:06:13','DD/MM/RR HH24:MI:SS'),null,'43','43',null,'696,43','S','0','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('41','7','1','453,57','2',to_date('27/08/15 17:27:52','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 17:27:52','DD/MM/RR HH24:MI:SS'),null,'40','40',null,'453,57','S','0','330,3');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('42','7','2','696,43','2',to_date('27/08/15 17:27:52','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 17:27:52','DD/MM/RR HH24:MI:SS'),null,'40','40',null,'696,43','S','0','495,45');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('52','9','1','453,57','2',to_date('28/08/15 15:15:46','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 15:15:46','DD/MM/RR HH24:MI:SS'),null,'40','40',null,'453,57','S','0','98,28');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('15','5','38','1000','2',to_date('27/08/15 11:06:17','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 11:06:17','DD/MM/RR HH24:MI:SS'),null,'40','40',null,'1000','S','0','450');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('18','5','38','1000','3',to_date('27/08/15 11:09:24','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 11:09:24','DD/MM/RR HH24:MI:SS'),null,'199','199',null,'1000','S','450','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('9','3','54','1000','2',to_date('25/08/15 15:55:58','DD/MM/RR HH24:MI:SS'),to_date('25/08/15 15:55:58','DD/MM/RR HH24:MI:SS'),null,'40','40',null,'1000','S','0','1000');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('12','3','54','1000','4',to_date('25/08/15 16:13:07','DD/MM/RR HH24:MI:SS'),to_date('25/08/15 16:13:07','DD/MM/RR HH24:MI:SS'),null,'42','42',null,'0','S','1000','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('16','5','38','1000','3',to_date('27/08/15 11:06:45','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 11:06:45','DD/MM/RR HH24:MI:SS'),null,'55','55',null,'1000','S','450','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('17','5','38','1000','2',to_date('27/08/15 11:06:45','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 11:06:45','DD/MM/RR HH24:MI:SS'),null,'55','55',null,'1000','S','0','450');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('55','9','1','355,29','7',to_date('28/08/15 15:59:53','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 15:59:53','DD/MM/RR HH24:MI:SS'),null,'42','42',null,'420,81','S','0','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('62','11','40','1000','3',to_date('28/08/15 17:50:15','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 17:50:15','DD/MM/RR HH24:MI:SS'),null,'55','55',null,'1000','S','300','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('63','11','40','1000','2',to_date('28/08/15 17:50:15','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 17:50:15','DD/MM/RR HH24:MI:SS'),null,'55','55',null,'1000','S','0','300');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('64','11','40','1000','4',to_date('28/08/15 17:50:59','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 17:50:59','DD/MM/RR HH24:MI:SS'),null,'20','20',null,'700','S','300','0');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('1','1','7','1000','2',to_date('24/08/15 12:21:37','DD/MM/RR HH24:MI:SS'),to_date('24/08/15 12:21:37','DD/MM/RR HH24:MI:SS'),null,'40','40',null,'1000','S','0','200');
Insert into FONDO_MARKETING_HISTORIA (ID,ID_SOLICITUD,ID_TO_FONDO,TO_OLD_SALDO,ID_FONDO_HISTORY_REASON,CREATED_AT,UPDATED_AT,DELETED_AT,CREATED_BY,UPDATED_BY,DELETED_BY,TO_NEW_SALDO,ID_TIPO_TO_FONDO,OLD_RETENCION,NEW_RETENCION) values ('4','1','7','1000','4',to_date('24/08/15 12:25:42','DD/MM/RR HH24:MI:SS'),to_date('24/08/15 12:25:42','DD/MM/RR HH24:MI:SS'),null,'42','42',null,'800','S','200','0');
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
REM INSERTING into FONDO_MKT_PERIODO_HISTORIA
SET DEFINE OFF;
Insert into FONDO_MKT_PERIODO_HISTORIA (ID,PERIODO,SUBCATEGORIA_ID,SALDO_INICIAL,SALDO_FINAL,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,RETENCION_INICIAL,RETENCION_FINAL) values ('3','201508','1','114000','113750',to_date('27/08/15 17:40:55','DD/MM/RR HH24:MI:SS'),to_date('27/08/15 17:42:58','DD/MM/RR HH24:MI:SS'),'11','42','0','575,75');
Insert into FONDO_MKT_PERIODO_HISTORIA (ID,PERIODO,SUBCATEGORIA_ID,SALDO_INICIAL,SALDO_FINAL,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,RETENCION_INICIAL,RETENCION_FINAL) values ('5','201508','6','114000','113800',to_date('28/08/15 16:45:17','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 16:49:00','DD/MM/RR HH24:MI:SS'),'11','42','0','0');
Insert into FONDO_MKT_PERIODO_HISTORIA (ID,PERIODO,SUBCATEGORIA_ID,SALDO_INICIAL,SALDO_FINAL,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,RETENCION_INICIAL,RETENCION_FINAL) values ('2','201508','28','114000','113700',to_date('24/08/15 17:24:29','DD/MM/RR HH24:MI:SS'),to_date('24/08/15 17:26:47','DD/MM/RR HH24:MI:SS'),'11','42','0','0');
Insert into FONDO_MKT_PERIODO_HISTORIA (ID,PERIODO,SUBCATEGORIA_ID,SALDO_INICIAL,SALDO_FINAL,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,RETENCION_INICIAL,RETENCION_FINAL) values ('4','201508','7','114000','113990',to_date('28/08/15 10:22:26','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 10:23:11','DD/MM/RR HH24:MI:SS'),'11','42','0','0');
Insert into FONDO_MKT_PERIODO_HISTORIA (ID,PERIODO,SUBCATEGORIA_ID,SALDO_INICIAL,SALDO_FINAL,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,RETENCION_INICIAL,RETENCION_FINAL) values ('1','201508','11','56000','54067,24',to_date('24/08/15 12:21:37','DD/MM/RR HH24:MI:SS'),to_date('28/08/15 18:01:34','DD/MM/RR HH24:MI:SS'),'40','19','0','1000');
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
REM INSERTING into FONDO_SUPERVISOR
SET DEFINE OFF;
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('41','40','11','35','1000',null,to_date('18/08/15 02:41:16','DD/MM/RR HH24:MI:SS'),null,'20',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('42','40','11','83','1000',null,to_date('03/08/15 17:35:19','DD/MM/RR HH24:MI:SS'),null,'55',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('1','40','11','79','420,81',null,to_date('28/08/15 15:59:53','DD/MM/RR HH24:MI:SS'),null,'42',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('2','40','11','73','696,43',null,to_date('27/08/15 17:40:55','DD/MM/RR HH24:MI:SS'),null,'11',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('3','40','11','94','1000',null,to_date('24/07/15 10:56:40','DD/MM/RR HH24:MI:SS'),null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('4','40','11','16','1000',null,to_date('07/08/15 17:51:14','DD/MM/RR HH24:MI:SS'),null,'43',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('5','40','11','101','1000',null,to_date('17/08/15 22:24:52','DD/MM/RR HH24:MI:SS'),null,'20',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('6','40','11','19','1000',null,to_date('05/08/15 11:08:37','DD/MM/RR HH24:MI:SS'),null,'40',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('7','40','11','4','800',null,to_date('24/08/15 12:25:42','DD/MM/RR HH24:MI:SS'),null,'42',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('8','40','11','77','1000',null,to_date('11/08/15 17:25:44','DD/MM/RR HH24:MI:SS'),null,'20',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('9','40','11','21','1000',null,to_date('03/08/15 12:31:34','DD/MM/RR HH24:MI:SS'),null,'43',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('10','40','11','75','1000',null,to_date('04/08/15 17:13:24','DD/MM/RR HH24:MI:SS'),null,'55',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('11','40','11','91','1000',null,to_date('18/08/15 10:21:30','DD/MM/RR HH24:MI:SS'),null,'42',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('12','40','11','80','1000',null,to_date('14/08/15 15:38:27','DD/MM/RR HH24:MI:SS'),null,'13',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('14','40','11','88','1000',null,to_date('20/08/15 10:23:04','DD/MM/RR HH24:MI:SS'),null,'40',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('15','40','11','2','1000',null,to_date('05/08/15 15:09:32','DD/MM/RR HH24:MI:SS'),null,'199',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('16','40','11','26','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('17','40','11','8','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('18','40','11','1','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('19','40','11','30','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('20','40','11','90','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('21','40','11','96','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('22','40','11','95','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('23','40','11','18','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('24','40','11','23','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('25','40','11','49','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('26','40','11','92','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('27','40','11','29','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('28','40','11','12','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('29','40','11','27','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('30','40','11','33','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('31','40','11','9','1000',null,to_date('04/08/15 16:07:35','DD/MM/RR HH24:MI:SS'),null,'42',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('32','40','11','102','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('33','40','11','105','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('34','40','11','38','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('35','40','11','14','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('36','40','11','93','1000',null,to_date('19/08/15 21:36:39','DD/MM/RR HH24:MI:SS'),null,'20',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('37','40','11','85','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('38','40','11','78','1000',null,to_date('27/08/15 17:05:26','DD/MM/RR HH24:MI:SS'),null,'19',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('39','40','11','37','1000',null,to_date('20/08/15 11:00:00','DD/MM/RR HH24:MI:SS'),null,'20',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('43','40','11','50','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('44','40','11','47','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('45','40','11','76','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('46','40','11','106','1000',null,to_date('24/07/15 13:53:28','DD/MM/RR HH24:MI:SS'),null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('47','40','11','52','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('48','40','11','53','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('49','40','11','55','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('50','40','11','59','1000',null,to_date('20/08/15 10:29:28','DD/MM/RR HH24:MI:SS'),null,'40',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('52','40','11','66','1000',null,to_date('20/08/15 10:59:52','DD/MM/RR HH24:MI:SS'),null,'20',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('53','40','11','54','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('55','40','11','69','1000',null,to_date('20/08/15 10:59:52','DD/MM/RR HH24:MI:SS'),null,'20',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('56','40','11','70','1000',null,null,null,null,null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('40','40','11','97','1000',null,to_date('28/08/15 18:01:34','DD/MM/RR HH24:MI:SS'),null,'19',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('51','40','11','61','900',null,to_date('28/08/15 15:33:21','DD/MM/RR HH24:MI:SS'),null,'42',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('54','40','11','72','250',null,to_date('27/08/15 12:27:11','DD/MM/RR HH24:MI:SS'),null,'42',null,null,'0');
Insert into FONDO_SUPERVISOR (ID,SUPERVISOR_ID,SUBCATEGORIA_ID,MARCA_ID,SALDO,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY,RETENCION) values ('13','40','11','81','1000',null,to_date('20/08/15 10:21:17','DD/MM/RR HH24:MI:SS'),null,'40',null,null,'0');
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
Insert into PARAMETRO (ID,DESCRIPCION,VALOR,MENSAJE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('1','N° DIAS DE LA ALERTA DEL REGISTRO DE GASTOS','7','esta pendiente de registrar gastos por mas de 1 semana desde que se registro el ultimo documento.',null,to_date('10/08/15 13:54:53','DD/MM/RR HH24:MI:SS'),null,null,null,null);
Insert into PARAMETRO (ID,DESCRIPCION,VALOR,MENSAJE,CREATED_AT,UPDATED_AT,CREATED_BY,UPDATED_BY,DELETED_AT,DELETED_BY) values ('2','N° DIAS DE LA ALERTA PARA FINALIZAR EL REGISTRO DE GASTOS','30','No se ha registrado los gastos de la solicitud por mas de 1 mes desde que se creo la solicitud.',null,to_date('17/07/15 18:54:22','DD/MM/RR HH24:MI:SS'),null,null,null,null);
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
