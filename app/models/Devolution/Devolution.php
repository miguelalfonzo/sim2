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

}