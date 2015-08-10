<?php

namespace System;

use \Eloquent;

class FondoMktHistory extends Eloquent
{
	
    protected $table = 'FONDO_MARKETING_HISTORIA';
    protected $primaryKey = 'id';
    
    public function nextId()
    {
        $lastId = FondoMktHistory::orderBy( 'id' , 'desc' )->first();
        if( is_null( $lastId ) )
            return 1;
        else
            return $lastId->id + 1;
    }

    protected static function order()
    {
        return FondoMktHistory::orderBy( 'updated_at' , 'DESC' , 'id' , 'DESC' )->get();
    }

    protected function solicitud()
    {
        return $this->belongsTo( '\Dmkt\Solicitud' , 'id_solicitud' );
    }

    protected function fromFund()
    {
        \Log::error( $this->id_tipo_to_fondo );
        \Log::error( $this->id_to_fondo );
        if ( $this->id_tipo_to_fondo == 'I' )
            return $this->belongsTo( '\Fondo\FondoInstitucional' , 'id_from_fondo' );
        elseif ( $this->id_tipo_to_fondo == GER_PROD )
            return $this->belongsTo( '\Fondo\FondoGerProd' , 'id_from_fondo' );
        elseif ( $this->id_tipo_to_fondo == SUP )
            return $this->belongsTo( '\Fondo\FondoSupervisor' , 'id_from_fondo' );
    }

    protected function toFund()
    {

        \Log::error( $this->id_tipo_to_fondo );
        \Log::error( $this->id_to_fondo );
        if ( $this->id_tipo_to_fondo == 'I' )
            return $this->belongsTo( '\Fondo\FondoInstitucional' , 'id_to_fondo' );
        elseif ( $this->id_tipo_to_fondo == GER_PROD )
            return $this->belongsTo( '\Fondo\FondoGerProd' , 'id_to_fondo' );
        elseif ( $this->id_tipo_to_fondo == SUP )
            return $this->belongsTo( '\Fondo\FondoSupervisor' , 'id_to_fondo' );
    }
}