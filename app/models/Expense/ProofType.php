<?php

namespace Expense;

use \Eloquent;

class ProofType extends Eloquent{
	protected $table = 'DMKT_RG_TIPO_COMPROBANTE';
	protected $primaryKey = 'idcomprobante';
	public $timestamps = false;
	public function lastId(){
		$lastId = ProofType::orderBy('idcomprobante','desc')->first();
		if($lastId == null){
            return 0;
        }else{
            return $lastId->idcomprobante;
        }
	}
}