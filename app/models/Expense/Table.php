<?php

namespace Expense;

use \Eloquent;

class Table extends Eloquent
{

	protected $table = TB_VTA_TABLAS;
	
	protected static function getIGV()
	{
		return Table::where('tipo','143')->where('codigo' , '1' )->first();	
	}

}