<?php

namespace Expense;

use \Eloquent;

class Mark extends Eloquent
{
    protected $table= 'SIM_MARCA';
    protected $primaryKey = 'id';
   
   	protected static function lastId()
   	{
   		$mark = Mark::orderBy('id' , 'desc' )->first();
   		if ( $mark == null )
   			return 0;
   		else
   			return $mark->id;
   	} 
}