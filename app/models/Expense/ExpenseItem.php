<?php

namespace Expense;

use \Eloquent;

class ExpenseItem extends Eloquent{

    protected $table= 'DMKT_RG_GASTOS_ITEM';
    protected $primaryKey = null;
    public $incrementing = false;

    function idExpenseType(){
		return $this->hasOne('ExpenseType','idtipogasto','tipo_gasto');
	}

}