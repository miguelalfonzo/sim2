<?php

namespace System;

use \Eloquent;

class FondoMktHistory extends Eloquent
{
	
    protected $table = TB_FONDO_MARKETING_HISTORIAL;
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
        if ( $this->id_tipo_to_fondo == 'I' )
            return $this->belongsTo( '\Fondo\FondoInstitucional' , 'id_from_fondo' );
        elseif ( $this->id_tipo_to_fondo == GER_PROD )
            return $this->belongsTo( '\Fondo\FondoGerProd' , 'id_from_fondo' );
        elseif ( $this->id_tipo_to_fondo == SUP )
            return $this->belongsTo( '\Fondo\FondoSupervisor' , 'id_from_fondo' );
    }

    public function toFund()
    {
        if ( $this->id_tipo_to_fondo == 'I' )
            return $this->belongsTo( '\Fondo\FondoInstitucional' , 'id_to_fondo' );
        elseif ( $this->id_tipo_to_fondo == GER_PROD )
            return $this->belongsTo( '\Fondo\FondoGerProd' , 'id_to_fondo' );
        elseif ( $this->id_tipo_to_fondo == SUP )
            return $this->belongsTo( '\Fondo\FondoSupervisor' , 'id_to_fondo' );
    }

    public function fromSupFund()
    {
        return $this->belongsTo( '\Fondo\FondoSupervisor' , 'id_to_fondo' );    
    }

    public function fromGerProdFund()
    {
        return $this->belongsTo( '\Fondo\FondoGerProd' , 'id_to_fondo' );    
    }

    public function fromInstitutionFund()
    {
        return $this->belongsTo( '\Fondo\FondoInstitucional' , 'id_to_fondo' );    
    }    

    protected function updatedBy()
    {
        return $this->belongsTo( 'User' , 'updated_by' );
    }

    protected function fondoMktHistoryReason()
    {
        return $this->belongsTo( '\Fondo\FondoMktHistoryReason' , 'id_fondo_history_reason' );
    }
}
