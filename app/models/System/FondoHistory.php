<?php

namespace System;

use \Eloquent;

class FondoHistory extends Eloquent{
	
    protected $table = 'FONDO_HISTORIA';
    protected $primaryKey = 'id';
    
    protected function getUpdatedAtAttribute( $attr )
    {
        return \Carbon\Carbon::parse( $attr )->format('Y-m-d H:i');
    }

    public function nextId()
    {
        $nextId = FondoHistory::orderBy( 'id' , 'desc' )->first();
        if ( is_null( $nextId ) )
            return 1;
        else
            return $nextId->id + 1;
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