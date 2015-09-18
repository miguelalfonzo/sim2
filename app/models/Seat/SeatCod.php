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
    
    protected static function generateSeatCod( $year , $origen )
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

    	$seatCod = new SeatCod;
    	$seatCod->anio   = $year;
    	$seatCod->origen = $origen;
    	$seatCod->numasiento = $seat;
    	$seatCod->usuario = 'JORTIZ';
    	$seatCod->save();
    	return $seat;
    }

}
    