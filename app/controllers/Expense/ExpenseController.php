<?php

namespace Expense;

use \BaseController;
use \View;
use \Dmkt\Activity;
use \Dmkt\Solicitude;
use \Expense\ProofType;
use \Expense\ExpenseType;
use \Input;

class ExpenseController extends BaseController{

	public function show($token){
		//depositado es 5
		
		$solicitude  = Solicitude::where('token',$token)->firstOrFail();
		// $solicitude  = Solicitude::find('5');
		$typeProof   = ProofType::all();
		$typeExpense = ExpenseType::orderBy('idtipogasto','asc')->get();
		
		$proof   = array();
		$expense = array();

		foreach ($typeProof as $key => $value) {
			$proof[$key] = ['cod'=>$value->idcomprobante,'descripcion'=>mb_convert_case($value->descripcion, MB_CASE_TITLE, "UTF-8")];
		}

		foreach ($typeExpense as $key => $value) {
			$expense[$key] = ['cod'=>$value->idtipogasto,'descripcion'=>mb_convert_case($value->descripcion, MB_CASE_TITLE, "UTF-8")];
		}
		
		$dataActivity['solicitude'] = mb_convert_case($solicitude->titulo, MB_CASE_TITLE, "UTF-8");
		$dataActivity['id'] = $solicitude->idsolicitude;
		$dataActivity['typeActivity'] = mb_convert_case($solicitude->subtype->nombre, MB_CASE_TITLE, "UTF-8");
		// $dataActivity['idDeposit'] = $activity->deposit->num_transferencia;
		$dataActivity['typeMoney'] = mb_convert_case($solicitude->typemoney->descripcion, MB_CASE_TITLE, "UTF-8");
		// $dataActivity['totalDeposit'] = $activity->deposit->total;
		$dataActivity['simbolMoney'] = mb_convert_case($solicitude->typemoney->simbolo, MB_CASE_UPPER, "UTF-8");
		$dataActivity['expenseType'] = $expense;
		$dataActivity['proofType'] = $proof;

		$date = $this->getDay();

		$data = ['date'=>$date,'activity'=>$dataActivity];

		// var_dump($data['activity']['solicitude']);
		// return;
		// die;

		return View::make('Expense.register')->with('data',$data);
	}

	public function registerExpense(){
		$data = Input::get('data');
		return "OK";
	}

	private function getDay(){
		$currentDate = getdate();
		$toDay = $currentDate['mday']."/".str_pad($currentDate['mon'],2,'0',STR_PAD_LEFT)."/".$currentDate['year'];
		$lastDay = '06/'.str_pad(($currentDate['mon']+1),2,'0',STR_PAD_LEFT).'/'.$currentDate['year'];
		$date = ['toDay'=>$toDay,'lastDay'=> $lastDay];
		return $date;
	}
}