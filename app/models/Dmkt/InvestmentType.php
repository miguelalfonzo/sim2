<?php

namespace Dmkt;
use \Eloquent;

class InvestmentType extends Eloquent
{
    protected $table = 'SIM_TIPO_INVERSION';
    protected $primaryKey = 'id';

    protected static function order()
    {
    	return InvestmentType::orderBy('id','asc')->get();
    }

    protected function investmentActivity()
    {
    	return $this->hasMany('Dmkt\InvestmentActivity' , 'id_inversion' , 'id' );
    }
}