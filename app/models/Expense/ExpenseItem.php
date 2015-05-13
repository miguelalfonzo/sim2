<?php

namespace Expense;

use \Eloquent;

class ExpenseItem extends Eloquent{

    protected $table= 'GASTO_ITEM';
    protected $primaryKey = 'id';
    
    public function lastId()
    {
    	$lastId = ExpenseItem::orderBy('id','desc')->first();
		if( $lastId == null )
            return 0;
        else
            return $lastId->id;
    }

    function idExpenseType()
    {
		return $this->hasOne('ExpenseType','idtipogasto','tipo_gasto');
	}

}