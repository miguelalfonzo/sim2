<?php 

namespace Expense;

use \Eloquent;
use \Dmkt\Activity;
use \Expense\ProofType;
use \Expense\ExpenseItem;

class Expense extends Eloquent {

	protected $table= 'DMKT_RG_GASTOS';
	protected $primaryKey = 'idgasto';

	public function idSolicitude(){
		return $this->hasOne('Dmkt\Activity','idsolicitud','idsolicitud');
	}

	public function idProofType(){
		return $this->hasOne('Expense\ProofType','idcomprobante','idcomprobante');
	}

    public function items(){
        return $this->hasMany('Expense\ExpenseItem','idgasto','idgasto');
    }

    public function lastId(){
		$lastId = Expense::orderBy('idgasto','desc')->first();
		if($lastId == null){
            return 0;
        }else{
            return $lastId->idgasto;
        }
	}

	public function lastIdIgv(){
		$lastIdIgv = Expense::orderBy('idigv','desc')->first();
		if($lastIdIgv == null){
            return 0;
        }else{
            return $lastIdIgv->idigv;
        }	
	}

}