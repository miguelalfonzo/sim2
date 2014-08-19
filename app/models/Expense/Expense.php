<?php 

namespace Expense;
use \Eloquent;
use \Common\State;

class Expense extends Eloquent {

	protected $table= 'DMKT_RG_GASTOS';

	public function idEstado(){
		return $this->hasOne('\Common\State','idestado','estado_idestado');
	}

	public function idMoney(){
		return $this->hasOne('\Common\TypeMoney','idtipomoneda','tipo_moneda');
	}

	public function idActivity(){
		return $this->hasOne('\Common\SubTypeActivity','idsubtipoactividad','actividad_idactividad');
	}
}
