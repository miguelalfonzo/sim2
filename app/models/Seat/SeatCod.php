<?php

namespace Seat;

use \Eloquent;

class SeatCod extends Eloquent
{
	
    protected $table = TB_BAGO_COD_ASIENTO;
    protected $primaryKey = null;
    public $incrementing = null;
    public $timestamps = false;
    protected $dates = [ 'f_proceso' , 'f_trn' ]; 
    
    protected static function generateTelecreditoSeatCod( $year , $origen )
    {
    	$lastSeat = SeatCod::where( 'anio' , $year )->where( 'origen' , $origen )->orderBy( 'numasiento' , 'desc' )->first();
    	if ( is_null( $lastSeat ) )
    	{
    		$seat = $origen . '0001';
    	}
    	else
    	{
    		$seat = $lastSeat + 1;
    	}

    	return Self::registerSeat( $seat , $year , $origen , 0 );
    }

    private static function registerSeat( $seat , $year , $origen , $i )
    {
        $seatCod = new SeatCod;
        $seatCod->anio   = $year;
        $seatCod->origen = $origen;
        $seatCod->numasiento = $seat;
        $seatCod->usuario = 'JORTIZ';
        if ( $seatCod->save() )
        {
            return $seatCod->numasiento;
        }
        else
        {
            $seat ++;
            $i ++;
            if( $i === 3 )
            {
                return 0;
            }
            else
            {
                return $this->registerSeat( $seat , $i );
            }
        }
    }

}
    