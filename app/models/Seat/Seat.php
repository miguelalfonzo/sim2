<?php

namespace Seat;

use \Eloquent;
use \Expense\Entry;

class Seat extends Eloquent
{
	
    protected $table = TB_BAGO_ASIENTO;
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $dates = [ 'penfchmod' ]; 

    protected static function generateManualSeatCod( $year , $origen )
    {
        $lastSeat = Seat::select( 'substr( penclave , 0 , 5 ) numasiento' )->where( 'penaamovim' , $year )->where( 'penclave' , 'like' , $origen . '%' )
                    ->orderBy( 'penclave' , 'desc' )->first();
        if ( is_null( $lastSeat ) )
        {
            $seat = $origen . '0001';
        }   
        else
        {
            $seat = $lastSeat->numasiento + 1;
        }
        return $seat;
    }

    protected static function registerSeat( $systemSeat , $seatPrefix , $key , $date , $state )
    {
        $seat                = new Seat;
        $seat->penclave      = $seatPrefix . str_pad( ( $key + 1 ) , 4 , 0 , STR_PAD_LEFT ); //CLAVE DE BAGO DEL ASIENTO
        $seat->pencodtar1    = '1'; // '1' Codigo Fijo
        $seat->pentipomovim  = 'VS';
        $seat->penddmovim    = $date->day;// DIA DEL REGISTRO DEL ASIENTO , CIERRE CONTABLE GENERALMENTE 31 o 30 o 29 o 28
        $seat->penmmmovim    = $date->month;// MES DEL REGISTRO DEL ASIENTO , MES DEL REGISTRO DEL ASIENTO
        $seat->penaamovim    = $date->year; //AÑO DE REGISTRO DEL ASIENTO
        $seat->penctaextern  = $systemSeat->num_cuenta; //CUENTA CONTABLE 7 DIGITOS
        $seat->pencodcompor  = Self::blankspace( $systemSeat->cc ); // CODIGO DEL TIPO DE DOCUMENTO ESTABLECIDO POR SUNAT| '00' => NO SUSTENTABLE | CODIGOS DIFERENTES PARA FACTURAS , BOLETAS , TICKET , RECIBO POR HONORARIOS 
        $seat->penddcomporg  = $date->day; // DIA DEL DOCUMENTO
        $seat->penmmcomporg  = $date->month; // MES DEL DOCUMENTO  
        $seat->penaacomporg  = $date->year; // AÑO DEL DOCUMENTO
        $seat->pencodtar2    = '2'; // SETEADO UNICO VALOR
        $seat->penleyendafi  = Self::blankspace( $systemSeat->leyenda_fj ); // CENTRO DE COSTOS DE BAGO PARA LOS DOCUMENTOS 
        $seat->penleyendava  = substr( Self::blankspace( $systemSeat->leyenda ) , 50 ); // GLOSA , DESCRIPCION DEL ASIENTO | MAXIMO 50 CARACTERES
        $seat->pentipoimpor  = $systemSeat->d_c; // CODIGO CONTABLE DE LAS CUENTAS "T" | D => DEBE | H => HABER
        $seat->penimportemo  = $systemSeat->importe; //MONTO
        $seat->pencodigoiva  = Self::blankspace( $systemSeat->iva ); // Codigo del sistema para los asientos de documentos con IGV | N6 PARA ITEM Y I6 PARA IGB
        $seat->pencodprovee  = Self::blankspace( $systemSeat->cod_pro ); //CODigo del sistema para los asientos de documentos con IGV = "90000"
        $seat->pencoddivisi  = $systemSeat->cod_pro == 90000 ? 1 : ' '; // or '1' 
        $seat->penestado     = $state; //The First line has 'C'
        $seat->pennrocompro  = is_null( $seat->prefijo ) ? ' ' : str_pad( $seat->prefijo , 0 , 3 ) . $seat->cbte_prov;   // 3 digitos para la serie del comprobante y el numero del Comprobante
        $seat->pennombrepro  = Self::blankspace( $systemSeat->nom_prov ); // RAZON SOCIAL SOLO PARA DOCUMENTOS
        $seat->pencoddocpro  = Self::blankspace( $systemSeat->cod ); // CODIGO del sistema para documentos con igv = "80"
        $seat->pennrodocpro  = Self::blankspace( $systemSeat->ruc );  // RUC SOLO PARA DOCUMENTOS
        $seat->pencanthojas  = Self::blankspace( $systemSeat->tipo_responsable ); //CODIGO DE TIPO DE RESPONSABLE 1 , 2, 4 PARA LA SUNAT DE USUARIOS EFECTOS A OPERACION GRAVADA => 1  , NO GRAVADA => 2  O AMBOS = 4
        $seat->pencondiva    = $systemSeat->tipo_responsable == 1 ? 'IN' : ( $seat->tipo_responsable == 2 ? 'NI' : ( $seat->tipo_responsable == 4 ? 'MO' : ' ' ) ); //Depende de la anterior columna para 1 => IN , 2 => NI y 4 => MO
        $seat->pengentarjeta = 'N' ; // Flag 'N' y 'S' . indica estado para el sistema de bago N => NO Y S => SI
        $seat->penusuario    = 'JORTIZ' ;// USUARIO 
    	$seat->pennrocompor  = ' '; //CORRELATIVO POR DOCUMENTO DE BAGO PARA CONTROL DOCUMENTARIO , SE INGRESARA EN EL SISTEMA DEV DE BAGO
        $seat->pencodmoneda  = ' '; // CODIGO DE REGISTRO PARA LOS ASIENTOS EN DOLARES => 02 
    	$seat->pentipocambio = ' '; // TIPO DE CAMBIO PARA CUANDO EL ASIENTO ES EN DOLARES
    	//$seat->penimporte2 = ' '; // IMPORTE EN SOLES DEL ASIENTO CUANDO 
    	if ( $seat->save() )
        {
            $updateSystemSeat = Entry::find( $systemSeat->id );
            $updateSystemSeat->penclave = $seat->penclave;
            $updateSystemSeat->estado   = 1;
            if ( $updateSystemSeat->save() )
            {
                return array( status => ok );
            }
            else
            {
                return array( status => error );
            }
        }
        else
        {
            return array( status => error );
        }
    }

    private static function blankspace( $value )
    {
        if ( is_null( $value ) || empty( trim( $value ) ) )
        {
            return ' ';
        }
        else
        {
            return $value;
        }
    }

}
    