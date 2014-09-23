<?php

namespace Common;

use \Eloquent;
use \Dmkt\Solicitude;

class Deposit extends Eloquent{

	protected $table = 'DMKT_RG_DEPOSITO';
	protected $primaryKey = 'iddeposito';

	public function iddeposit()
	{
		return $this->hasOne('Dmkt\Solicitude','idsolicitud','idsolicitud');
	}
}