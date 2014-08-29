<?php

namespace Expense;

use \Eloquent;

class ExpenseDetail extends Eloquent{

	protected $table = "DMKT_RG_GASTOS_ITEM";
	protected $primaryKey = null;
	public $incrementing = false;

	function idExpense(){
		return $this->hasOne('Expense','idgasto','idgasto');
	}

	function idExpenseType(){
		return $this->hasOne('ExpenseType','idtipogasto','tipo_gasto');
	}

}