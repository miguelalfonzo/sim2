<?php

namespace Common;
use \Eloquent;

class LedgerAccount extends Eloquent {

    protected $table = 'DMKT_RG_CUENTA';
    protected $primaryKey = 'idcuenta';

    public function lastId()
    {
	  	$lastId = LedgerAccount::orderBy('id','desc')->first();
	  	if($lastId == null)
  		{
        	return 0;
    	}
    	else
    	{
        	return $lastId->idcuenta;
    	}
 	}
}