<?php

namespace Common;
use \Eloquent;

class Fondo extends Eloquent {

    protected $table = 'DMKT2_RG_FONDO';
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

    public static function supFondos()
    {
        return Fondo::all();
    }

    public static function gerProdFondos()
    {
        return Fondo::where('trim(idusertype)', '<>' ,SUP )->get();
    }

    protected function typeMoney()
    {
        return $this->hasOne('Common\TypeMoney','idtipomoneda','idtipomoneda');
    }
}