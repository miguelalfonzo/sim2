<?php

namespace Expense;

use \Eloquent;
use \Carbon\Carbon;

class ChangeRate extends Eloquent
{
    protected $table = TB_TIPO_DE_CAMBIO;
    
    protected function getFechaAttribute($value)
    {
        return date_format( date_create( $value ), 'd/m/Y' );
    }

    protected static function getTc()
    {
        return ChangeRate::where('moneda' , 'DO')->orderBy('fecha','desc')->first();
    }

    protected static function getDayTc( $date )
    {
        return ChangeRate::where( 'moneda' , 'DO' )->where( 'fecha' , $date )->first();
    }

    protected static function getLastDayDolar( $date )
    {
        return ChangeRate::where( 'moneda' , 'DO' )->where( 'fecha + 1' , $date->startOfDay() )->first()->compra;
    }

}