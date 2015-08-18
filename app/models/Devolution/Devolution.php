<?php

namespace Devolution;
use \Eloquent;

class Devolution extends Eloquent
{

    protected $table= TB_DEVOLUCION;
    protected $primaryKey = 'id';

    public function nextId()
    {
    	$nextId = Devolution::select('id')->orderBy( 'id' , 'desc' )->first();
        if ( is_null( $nextId ) )
            return 1;
        else
            return $nextId->id + 1;
    }

    protected function type()
    {
        return $this->belongsTo( 'Devolution\DevolutionType' , 'id_tipo_devolucion' );
    }

    protected function state()
    {
        return $this->belongsTo( 'Devolution\DevolutionState' , 'id_estado_devolucion' );
    }

}