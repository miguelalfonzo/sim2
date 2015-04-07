<?php

namespace Common;
use \Eloquent;

class Fondo extends Eloquent {

    protected $table = 'DMKT_RG_FONDO';
    protected $primaryKey = null;
    public $incrementing = false;

    public function lastId()
    {
	  	$lastId = Fondo::orderBy('id','desc')->first();
	  	if($lastId == null)
  		{
        	return 0;
    	}
    	else
    	{
        	return $lastId->idgasto;
    	}
 	}
}