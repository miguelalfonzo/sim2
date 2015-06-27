<?php

namespace System;

use \Eloquent;

class FondoHistory extends Eloquent{
	
    protected $table = 'SIM_FONDO_HISTORIA';
    protected $primaryKey = 'id';
    
    protected function getUpdatedAtAttribute( $attr )
    {
        return \Carbon\Carbon::parse( $attr )->format('Y-m-d H:i');
    }

    protected function solicitud()
    {
        return $this->hasOne( 'Dmkt\Solicitud' , 'id' , 'id_solicitud' );
    }

    protected function fondo()
    {
        return $this->hasOne( 'Common\Fondo' , 'id' , 'id_fondo' );
    }

}