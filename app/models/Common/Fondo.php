<?php

namespace Common;
use \Eloquent;

class Fondo extends Eloquent {

    protected $table = 'DMKT_RG_FONDO';
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

    public function bagoAccount()
    {
        return $this->hasOne( 'Expense\PlanCta' , 'ctactaextern' , 'num_cuenta' );
    }

    public static function supFondos()
    {
        return Fondo::where( 'trim(idusertype)' , SUP )->whereNotNull('num_cuenta')->get();
    }

    public static function gerProdFondos()
    {
        return Fondo::where('trim(idusertype)', GER_PROD )->whereNotNull('num_cuenta')->get();
    }

    public static function asisGerFondos()
    {
        return Fondo::where('trim(idusertype)' , ASIS_GER )->whereNotNull('num_cuenta')->get();
    }

    protected function typeMoney()
    {
        return $this->hasOne('Common\TypeMoney','id','idtipomoneda');
    }

    protected function account()
    {
        return $this->hasOne('Dmkt\Account','num_cuenta','num_cuenta');
    }

    protected function userType()
    {
        return $this->hasOne( 'Common\TypeUser' , 'codigo' , 'idusertype' );
    }
}