<?php

namespace Client;
use \Eloquent;

class DistrimedClient extends Eloquent
{

    protected $table = 'VTADIS.CLIENTES';
    protected $primaryKey = 'clcodigo';

    protected function getFullNameAttribute()
    {
    	if ( $this->clclase == 1 )
        	return $this->clrut . '-' . $this->clnombre;
    	else if ( $this->clclase == 6 )
    		return $this->clrut . '-' . $this->clnombre;
    	else
    		return $this->clrut . '-' . $this->clnombre;	
    }
}