<?php

namespace Expense;

use \BaseController;
use \View;
use \Dmkt\Activity;
use \Dmkt\Solicitude;

class ExpenseController extends BaseController{

	public function show(){
		
		$id = 1;

		$activity = Activity::find($id);
		$solicitude = Solicitude::find($activity->idsolicitud);

		var_dump($activity->iddeposito);
		// var_dump($solicitude->descripcion);
		return;
		die;



		$date = $this->getDay();
		$dataActivity['type'] = 'Dólares';
		$dataActivity['simbolo'] = '$';

		$data = ['date'=>$date,'activity'=>$dataActivity];

		return View::make('Expense.register')->with('data',$data);
	}

	private function getDay(){
		$currentDate = getdate();
		$toDay = $currentDate['mday']."/".str_pad($currentDate['mon'],2,'0',STR_PAD_LEFT)."/".$currentDate['year'];
		$lastDay = '06/'.str_pad(($currentDate['mon']+1),2,'0',STR_PAD_LEFT).'/'.$currentDate['year'];
		$date = ['toDay'=>$toDay,'lastDay'=> $lastDay];
		return $date;
	}
}