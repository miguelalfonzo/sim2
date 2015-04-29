<?php

namespace Expense;

use \Eloquent;

class ChangeRate extends Eloquent
{
    protected $table= 'B3O.CXP_TC';
    
    protected function getFechaAttribute($value)
    {
        return date_format( date_create( $value ), 'd/m/Y' );
    }

    protected static function getTc()
    {
        return ChangeRate::where('moneda' , 'DO')->orderBy('fecha','desc')->first();
    }

    protected static function getDateTc( $date )
    {
    	$tc = ChangeRate::where('moneda' , 'DO' )->where( 'fecha' , $date)->first();
    	if ( is_null( $tc ) )
    		return ChangeRate::getTc();
    	else
    		return $tc;
    }

}