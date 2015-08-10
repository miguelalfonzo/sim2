<?php

namespace Fondo;

use \Eloquent;

class Fondo extends Eloquent {

    protected $table = TB_FONDO_CONTABLE;
    protected $primaryKey = 'id';

    public function getIdusertypeAttribute($value)
    {
        return trim($value);
    }

    public function lastId()
    {
	  	$lastId = Fondo::orderBy('id','desc')->first();
	  	if($lastId == null)
        	return 0;
    	else
        	return $lastId->id;
 	}

    protected static function order()
    {
        return Fondo::orderBy( 'updated_at' , 'desc' )->get();
    }

    public function bagoAccount()
    {
        return $this->hasOne( 'Expense\PlanCta' , 'ctactaextern' , 'num_cuenta' );
    }

    protected function account()
    {
        return $this->hasOne( 'Dmkt\Account' , 'num_cuenta' , 'num_cuenta' );
    }

}