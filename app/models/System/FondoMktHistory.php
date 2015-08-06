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
}