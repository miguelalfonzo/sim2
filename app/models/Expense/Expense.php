<?php 

namespace Expense;
use \Eloquent;
use \Common\State;
use \Common\TypeMoney;
use \Common\SubTypeActivity;

class Expense extends Eloquent {

	protected $table= 'DMKT_RG_GASTOS';
	protected $primaryKey = 'idgasto';

	public function idEstado(){
		return $this->hasOne('\Common\State','idestado','estado_idestado');
	}

	public function idMoney(){
		return $this->hasOne('\Common\TypeMoney','idtipomoneda','tipo_moneda');
	}

	public function idProofType(){
		return $this->hasOne('ProofType','idcomprobante','tipo_comprobante');
	}

	public function lastId(){
		$lastId = Expense::orderBy('idgasto','desc')->first();
		if($lastId == null){
            return 0;
        }else{
            return $lastId->idgasto;
        }
	}
}
