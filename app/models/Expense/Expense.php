<?php 

namespace Expense;
use \Eloquent;

class Expense extends Eloquent {

	protected $table= 'DMKT_RG_GASTOS';

	public function idEstado()
	{
		return $this->hasOne('');
	}

}
