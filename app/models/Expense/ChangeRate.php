<?php

namespace Expense;

use \Eloquent;

class ChangeRate extends Eloquent
{
    protected $table= 'B3O_CXP_TC';
    
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

    protected static function getTcv( $date )
    {
        $tc = ChangeRate::where('fecha' , $date)->where('moneda' ,'DO')->first();
        if ( is_null( $tc ) )
        {
            $tc = ChangeRate::getTc();
            return $tc->venta;
        }
        else
            return $tc->venta;
    }

}