<?php

namespace Dmkt;
use \Eloquent;
class TypeRetention extends Eloquent {

	protected $table = 'DMKT_RG_TIPO_RETENCION';
	protected $primaryKey = 'idtiporetencion';

	public function getDescripcionAttribute($value)
    {
        return ucwords( mb_strtolower( $value ) );
    }

    protected function account()
    {
    	return $this->hasOne('Dmkt\Account','id','idcuenta');
    }

}