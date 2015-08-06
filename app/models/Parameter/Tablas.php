<?php

namespace Parameter;

use \Eloquent;

class Tablas extends Eloquent
{
    protected $table= 'VTA.TABLAS';
    protected $primaryKey = NULL;

    protected static function findProduct( $id )
    {
    	return Tablas::where( 'tipo' , TIPO_FAMILIA )->where( 'codigo' , $id )->first();
    }
}