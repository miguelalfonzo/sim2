<?php

namespace Dmkt;
use \Eloquent;

class InvestmentActivity extends Eloquent
{
    protected $table = 'SIM_INVERSION_ACTIVIDAD';
    protected $primaryKey = 'id';

    protected static function order()
    {
    	return InvestmentActivity::orderBy('id','asc')->get();
    }

    protected function activity()
    {
    	return $this->hasOne( 'Dmkt\Activity' , 'id' , 'id_actividad' );
    }

    public function investment()
    {
    	return $this->hasOne( 'Dmkt\InvestmentType' , 'id' , 'id_inversion' );
    }
}