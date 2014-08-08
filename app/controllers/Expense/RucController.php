<?php
namespace Expense;

use \BaseController;
use \ruc;
use \Input;

class RucController extends BaseController{
    public function show(){
    	$rucConsult = Input::get('ruc');
    	if(strlen($rucConsult)<11)
    	{
    		return 0;
    	}
    	else
    	{
			$rucClass = new RUC;
			$data     = $rucClass->consultRUC($rucConsult);
			if(is_array($data))
			{
				return $data;
			}
			else
			{
				return 1;
			}
    	}
    }
}