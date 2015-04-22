<?php

namespace Expense;

use \Eloquent;

class ExpenseType extends Eloquent{

	protected $table     = 'DMKT_RG_TIPO_GASTO';
	protected $primarKey = 'id';

	protected static function order()
	{
		return ExpenseType::orderBy('id','asc')->get();	
	}
}