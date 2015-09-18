<?php

namespace Seat;

use \Eloquent;

class Seat extends Eloquent
{
	
    protected $table = TB_BAGO_ASIENTO;
    protected $primaryKey = null;
    public $incrementing = false;
    $protected $timestamps = false;

    protected static function registerSeat( $seat , $seatPrefix , $key , $date , $state )
    {
    	$seat = new Seat;
    	$seat->penclave = $seatPrefix . str_pad( $key , 4 , 0 , STR_PAD_LEFT ); //CLAVE DE BAGO DEL ASIENTO
    	$seat->pencodtar = '1'; // '1' Codigo Fijo
    	$seat->pentipomovim = 'VS';
    	$seat->penddmovim = $date->day;// DIA DEL REGISTRO DEL ASIENTO , CIERRE CONTABLE GENERALMENTE 31 o 30 o 29 o 28
    	$seat->penmmmovim = $date->month;// MES DEL REGISTRO DEL ASIENTO , MES DEL REGISTRO DEL ASIENTO
    	$seat->penaamovim = $date->year; //AÑO DE REGISTRO DEL ASIENTO
    	$seat->penctaextern = $seat->num_cuenta; //CUENTA CONTABLE 7 DIGITOS
    	$seat->pencodcompor = $seat->cc; // CODIGO DEL TIPO DE DOCUMENTO ESTABLECIDO POR SUNAT| '00' => NO SUSTENTABLE | CODIGOS DIFERENTES PARA FACTURAS , BOLETAS , TICKET , RECIBO POR HONORARIOS 
    	//$seat->pennrocompor CORRELATIVO POR DOCUMENTO DE BAGO PARA CONTROL DOCUMENTARIO , SE INGRESARA EN EL SISTEMA DEV DE BAGO
    	$seat->penddcomporg = $date->day; // DIA DEL DOCUMENTO
    	$seat->penmmcomporg = $date->month; // MES DEL DOCUMENTO  
    	$seat->penaacomporg = $date->year; // AÑO DEL DOCUMENTO
    	$seat->pencodtar2   = '2'; // SETEADO UNICO VALOR
    	$seat->penleyendafi = $seat->leyenda_fj; // CENTRO DE COSTOS DE BAGO PARA LOS DOCUMENTOS 
    	$seat->penleyendava = $seat->leyenda; // GLOSA , DESCRIPCION DEL ASIENTO
    	$seat->pentipoimport = $seat->d_c; // CODIGO CONTABLE DE LAS CUENTAS "T" | D => DEBE | H => HABER
    	$seat->penimportemo = $seat->importe; //MONTO
    	$seat->pencodigoiva = $seat->iva; // Codigo del sistema para los asientos de documentos con IGV | N6 PARA ITEM Y I6 PARA IGB
    	$seat->pencodprovee = $seat->cod_pro; //CODigo del sistema para los asientos de documentos con IGV = "90000"
    	$seat->pencoddivisi = ''; // or '1' 
    	$seat->penestado    = $state; //The First line has 'C'
    	$seat->pennrocompro = str_pad( $seat->cbte_prov , 7 , 0 , STR_PAD_LEFT );   //Numero del Comprobante
    	$seat->pennombrepro = $seat->nom_prov; // RAZON SOCIAL SOLO PARA DOCUMENTOS
    	$seat->pencoddocpro = $seat->cod; // CODIGO del sistema para documentos con igv = "80"
    	$seat->pennrodocpro = $seat->ruc;  // RUC SOLO PARA DOCUMENTOS
    	$seat->pencanthojas = $seat->tipo_responsable; //CODIGO DE TIPO DE RESPONSABLE 1 , 2, 4 PARA LA SUNAT DE USUARIOS EFECTOS A OPERACION GRAVADA => 1  , NO GRAVADA => 2  O AMBOS = 4
    	$seat->pencodiva    = $seat->tipo_responsable == 1 ? 'IN' : ( $seat->tipo_responsable == 2 ? 'NI' : ( $seat->tipo_responsable == 4 ? 'MO' : '' ) ); //Depende de la anterior columna para 1 => IN , 2 => NI y 4 => MO
    	$seat->pengentarjeta = 'N' ;// Flag 'N' y 'S' . indica estado para el sistema de bago N => NO Y S => SI
    	$seat->penusuario = 'JORTIZ' ;// USUARIO 
    	//$seat->penfchmod
    	//$seat->pencodmoneda // CODIGO DE REGISTRO PARA LOS ASIENTOS EN DOLARES => 02 
    	//$seat->pentipocambio // TIPO DE CAMBIO PARA CUANDO EL ASIENTO ES EN DOLARES
    	//$seat->penimporte2 // IMPORTE EN SOLES DEL ASIENTO CUANDO 
    	$seat->save();
    }

}
    