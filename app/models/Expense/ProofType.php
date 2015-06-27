<?php

namespace Expense;

use \Eloquent;

class ProofType extends Eloquent{
	protected $table = 'SIM_TIPO_COMPROBANTE';
	protected $primaryKey = 'id';

	public function lastId()
	{
		$lastId = ProofType::orderBy('id','desc')->first();
		if($lastId == null)
            return 0;
        else
            return $lastId->id;
	}

	protected static function order()
	{
		return ProofType::orderBy('id','asc')->get();
	}
}