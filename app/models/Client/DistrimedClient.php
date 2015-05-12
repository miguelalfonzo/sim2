<?php

namespace Client;
use \Eloquent;

class DistrimedClient extends Eloquent
{

    protected $table = 'VTADIS.CLIENTES';
    protected $primaryKey = 'clcodigo';

    protected function getFullNameAttribute()
    {
    	if ( $this->attributes->clclase == 1 )
        	return 'DISTRIBUIDOR: '.$this->attributes[ 'clrut' ].'-'.$this->attributes[ 'clnombre' ];
    	else if ( $this->attributes->clclase == 6 )
    		return 'BODEGA: '.$this->attributes[ 'clrut' ].'-'.$this->attributes[ 'clnombre' ];
    	else
    		return '--- : '.$this->attributes[ 'clrut' ].'-'.$this->attributes[ 'clnombre' ];	
    }
}