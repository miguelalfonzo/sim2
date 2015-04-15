<?php

namespace Expense;

use \Eloquent;

class ExpenseItem extends Eloquent{

    protected $table= 'DMKT_RG_GASTO_ITEM';
    protected $primaryKey = 'id';
    

    function idExpenseType(){
		return $this->hasOne('ExpenseType','idtipogasto','tipo_gasto');
	}

}