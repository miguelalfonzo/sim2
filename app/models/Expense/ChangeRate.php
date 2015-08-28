<?php

namespace Expense;

use \Eloquent;

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

}