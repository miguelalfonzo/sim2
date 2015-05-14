<?php

namespace Dmkt;
use \Eloquent;

class InvestmentActivity extends Eloquent
{
    protected $table = 'INVERSION_ACTIVIDAD';
    protected $primaryKey = 'id';

    protected static function order()
    {
    	return Investment_Activity::orderBy('id','asc')->get();
    }

    protected function activity()
    {
    	return $this->hasOne( 'Dmkt\SolicitudActivity' , 'id' , 'id_actividad' );
    }

    public function investment()
    {
    	return $this->hasOne( 'Dmkt\InvestmentType' , 'id' , 'id_inversion' );
    }
}