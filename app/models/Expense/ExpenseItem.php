<?php

namespace Expense;

use \Eloquent;

class ExpenseItem extends Eloquent{

    protected $table= 'GASTO_ITEM';
    protected $primaryKey = 'id';
    
    public function lastId()
    {
    	$lastId = ExpenseItem::orderBy('id','desc')->first();
		if( is_null( $lastId ) )
            return 0;
        else
            return $lastId->id;
    }

    function idExpenseType()
    {
		return $this->hasOne('ExpenseType','idtipogasto','tipo_gasto');
	}
}