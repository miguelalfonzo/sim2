<?php

namespace Expense;

use \Eloquent;

class PlanCta extends Eloquent
{
    protected $table= 'B3O_PLANCTA';
    protected $primaryKey = 'ctactaextern';
 
    protected function account()
    {
    	return $this->hasOne('Dmkt\Account' , 'num_cuenta');
    }

}