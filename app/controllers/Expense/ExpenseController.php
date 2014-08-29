<?php

namespace Expense;

use \BaseController;
use \View;
use \Dmkt\Activity;
use \Dmkt\Solicitude;
use \Expense\Expense;
use \Expense\ProofType;
use \Expense\ExpenseType;
use \Input;

class ExpenseController extends BaseController{

	public function show(){
		//depositado es 5
		$token = Input::get('token');

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
		
		$dataActivity['titulo'] = mb_convert_case($solicitude->titulo, MB_CASE_TITLE, "UTF-8");
		$dataActivity['monto'] = $solicitude->monto;
		$dataActivity['id'] = $solicitude->idsolicitud;
		$dataActivity['typeActivity'] = mb_convert_case($solicitude->subtype->nombre, MB_CASE_TITLE, "UTF-8");
		// $dataActivity['idDeposit'] = $activity->deposit->num_transferencia;
		$dataActivity['typeMoney'] = mb_convert_case($solicitude->typemoney->descripcion, MB_CASE_TITLE, "UTF-8");
		// $dataActivity['totalDeposit'] = $activity->deposit->total;
		$dataActivity['simbolMoney'] = mb_convert_case($solicitude->typemoney->simbolo, MB_CASE_UPPER, "UTF-8");
		$dataActivity['expenseType'] = $expense;
		$dataActivity['proofType'] = $proof;

		$date = $this->getDay();

		$data = ['date'=>$date,'activity'=>$dataActivity];

		return View::make('Expense.register')->with('data',$data);
	}

	public function registerExpense(){

		$expense = Input::get('data');
		$expenseJson = json_decode($expense);
		$row_expense = Expense::find($expenseJson->idsolicitude);
		$row_solicitude = Solicitude::find($expenseJson->idsolicitude);
		if($row_expense)
		{
			return 1;
		}
		else
		{
			$expense = new Expense;
			$expense->idgasto = $expense->lastId()+1;
			// $expense->idgasto = 1;
			$expense->idresponse = 1;
			$expense->num_comprobante = $expenseJson->number_proof;
			$expense->ruc = $expenseJson->ruc;
			$expense->razon = $expenseJson->razon;
			$expense->monto = $expenseJson->total_expense;
			if($expense->proof_type == '2')
			{
				$expense->igv = $expenseJson->igv;
				$expense->imp_serv = $expenseJson->imp_serv;
				$expense->sub_tot = $expenseJson->sub_total_expense;
			}
			$expense->estado_idestado = $row_solicitude->estado;
			$date = $expenseJson->date_movement;
	        list($d, $m, $y) = explode('/', $date);
	        $d = mktime(11, 14, 54, $m, $d, $y);
	        $date = date("Y/m/d", $d);
	        $expense->fecha_movimiento = $date;
			$expense->tipo_moneda = $row_solicitude->tipo_moneda;
			$expense->tipo_comprobante = $expenseJson->proof_type;
			$expense->save();
			return "Nuevo";
		}
	}

	private function getDay(){
		$currentDate = getdate();
		$toDay = $currentDate['mday']."/".str_pad($currentDate['mon'],2,'0',STR_PAD_LEFT)."/".$currentDate['year'];
		$lastDay = '06/'.str_pad(($currentDate['mon']+1),2,'0',STR_PAD_LEFT).'/'.$currentDate['year'];
		$date = ['toDay'=>$toDay,'lastDay'=> $lastDay];
		return $date;
	}
}