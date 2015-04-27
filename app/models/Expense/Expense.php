<?php 

namespace Expense;

use \Eloquent;
use \Dmkt\Activity;
use \Expense\ProofType;
use \Expense\ExpenseItem;

class Expense extends Eloquent {

	protected $table= 'DMKT_RG_GASTO';
	protected $primaryKey = 'id';

	public function idSolicitude(){
		return $this->hasOne('Dmkt\Activity','idsolicitud','idsolicitud');
	}

	protected function proof()
	{
		return $this->hasOne('Expense\ProofType','id','idcomprobante');
	}

    protected function items()
    {
        return $this->hasMany('Expense\ExpenseItem','idgasto','id');
    }

    public function lastId()
    {
		$lastId = Expense::orderBy('id','desc')->first();
		if($lastId == null)
            return 0;
        else
            return $lastId->id;
	}

	public function lastIdIgv()
	{
		$lastIdIgv = Expense::orderBy('idigv','desc')->first();
		if($lastIdIgv == null)
            return 0;
        else
            return $lastIdIgv->idigv;	
	}

}