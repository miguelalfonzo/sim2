<?php

namespace Expense;

use \BaseController;
use \View;
use \Dmkt\Activity;
use \Dmkt\Solicitude;
use \Expense\TypeProof;

class ExpenseController extends BaseController{

	public function show(){
		$id = 1;

		$activity = Activity::find($id);
		$solicitude = Solicitude::find($activity->idsolicitud);
		$typeProof = TypeProof::all();
		$proof = array();

		foreach ($typeProof as $key => $value) {
			$proof[$key] = ['cod'=>$value->idcomprobante,'descripcion'=>mb_convert_case($value->descripcion, MB_CASE_TITLE, "UTF-8")];
		}
		
		$dataActivity['description'] = mb_convert_case($solicitude->descripcion, MB_CASE_TITLE, "UTF-8");
		$dataActivity['typeActivity'] = mb_convert_case($solicitude->subtype->nombre, MB_CASE_TITLE, "UTF-8");
		$dataActivity['idDeposit'] = $activity->deposit->num_transferencia;
		$dataActivity['typeMoney'] = mb_convert_case($solicitude->typemoney->descripcion, MB_CASE_TITLE, "UTF-8");
		$dataActivity['totalDeposit'] = $activity->deposit->total;
		$dataActivity['simbolMoney'] = mb_convert_case($solicitude->typemoney->simbolo, MB_CASE_UPPER, "UTF-8");
		$dataActivity['typeProof'] = $proof;

		// var_dump($activity->iddeposito);
		// var_dump($solicitude->descripcion);
		// var_dump($dataActivity['codDeposito']);
		// var_dump($dataActivity);
		// return;
		// die;

		$date = $this->getDay();

		$data = ['date'=>$date,'activity'=>$dataActivity];

		// var_dump($data['activity']['typeProof']['0']);
		// return;
		// die;

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