<?php

namespace Dmkt;
use \Eloquent;

class SolicitudActivity extends Eloquent
{
    protected $table = 'ETIQUETA';
    protected $primaryKey = 'id';

    protected static function order()
    {
    	return SolicitudActivity::orderBy('id','asc')->get();
    }

    public function investmentActivity()
    {
    	return $this->hasMany('Dmkt\InvestmentActivity' , 'id_actividad' , 'id' );
    }
}