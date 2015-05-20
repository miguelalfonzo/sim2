<?php

namespace Dmkt;
use \Eloquent;

class Activity extends Eloquent
{
    protected $table = 'TIPO_ACTIVIDAD';
    protected $primaryKey = 'id';

    protected static function order()
    {
    	return Activity::orderBy('id','asc')->get();
    }

    public function investmentActivity()
    {
    	return $this->hasMany('Dmkt\InvestmentActivity' , 'id_actividad' , 'id' );
    }
}