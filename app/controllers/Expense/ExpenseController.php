<?php

namespace Expense;

use \BaseController;
use \View;

class ExpenseController extends BaseController{

	public function show(){
		$currentDate = getdate();
		$toDay = $currentDate['mday']."/".str_pad($currentDate['mon'],2,'0',STR_PAD_LEFT)."/".$currentDate['year'];
		$lastDay = '06/'.str_pad(($currentDate['mon']+1),2,'0',STR_PAD_LEFT).'/'.$currentDate['year'];
		
		$date = ['toDay'=>$toDay,'lastDay'=> $lastDay];

		return View::make('Expense.register')->with('date',$date);
	}
}