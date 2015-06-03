<?php

namespace Common;

use \Eloquent;
use \Dmkt\Solicitud;
use \Carbon\Carbon;

class Deposit extends Eloquent{

	protected $table = 'DEPOSITO';
	protected $primaryKey = 'id';
	
	protected function getUpdatedAtAttribute( $attr )
	{
		return Carbon::parse( $attr )->format('d/m/Y');
	}

	public function iddeposit()
	{
		return $this->hasOne('Dmkt\Solicitud','idsolicitud','idsolicitud');
	}

	public function lastId(){
		$lastId = Deposit::orderBy('id','desc')->first();
		if($lastId == null){
            return 0;
        }else{
            return $lastId->id;
        }
	}

	protected function account()
	{
		return $this->hasOne('Dmkt\Account','num_cuenta','num_cuenta');
	}

	protected function bagoAccount()
	{
		return $this->hasOne('Expense\PlanCta' , 'ctactaextern' , 'num_cuenta' );
	}
}