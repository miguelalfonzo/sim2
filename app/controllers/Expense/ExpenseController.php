<?php

namespace Expense;

use \BaseController;
use \View;
use \Dmkt\Activity;
use \Dmkt\Solicitude;
use \Expense\Expense;
use \Expense\ProofType;
use \Expense\ExpenseType;
use \Expense\ExpenseDetail;
use \Input;
use \DB;

class ExpenseController extends BaseController{

	private function getDay(){
		$currentDate = getdate();
		$toDay = $currentDate['mday']."/".str_pad($currentDate['mon'],2,'0',STR_PAD_LEFT)."/".$currentDate['year'];
		$lastDay = '06/'.str_pad(($currentDate['mon']+1),2,'0',STR_PAD_LEFT).'/'.$currentDate['year'];
		$date = ['toDay'=>$toDay,'lastDay'=> $lastDay];
		return $date;
	}

	public function show(){
		//deposit is 5
		$token = Input::get('token');
		$solicitude  = Solicitude::where('token',$token)->firstOrFail();
		$typeProof   = ProofType::all();
		$typeExpense = ExpenseType::orderBy('idtipogasto','asc')->get();
		
		if($solicitude->idgasto)
		{
			$rows_expense = Expense::where('idsolicitud','=',$solicitude->idgasto)->get();
			foreach ($rows_expense as $key => $value) {
				$dataActivity['regProof'][$key] = $value->idProofType->descripcion;
				$dataActivity['regRuc'][$key] = $value->ruc; 
				$dataActivity['regRazon'][$key] = $value->razon; 
				$dataActivity['regNumberProof'][$key] = $value->num_comprobante;
				$dataActivity['regDate'][$key] = $value->fecha_movimiento;
				$dataActivity['regTotal'][$key] = $value->monto;
			}
		}

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
		// var_dump($expenseJson->quantity);die;

		$row_solicitude = Solicitude::find($expenseJson->idsolicitude);

		$expense = new Expense;
		//Detail Expense
		$idgasto = $expense->lastId()+1;
		$quantity = $expenseJson->quantity;
		$description = $expenseJson->description;
		$type_expense = $expenseJson->type_expense;
		$total_item = $expenseJson->total_item;
		//Header Expense
		$expense->idgasto = $idgasto;
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
		$expense->idsolicitud = $row_solicitude->idsolicitud;
		
		if($expense->save())
		{
			try {
				DB::transaction (function() use ($idgasto,$quantity,$description,$type_expense,$total_item){
					for($i=0;$i<count($quantity);$i++)
					{
						$expense_detail = new ExpenseDetail;
						$expense_detail->idgasto = $idgasto;
						$expense_detail->cantidad = $quantity[$i];
						$expense_detail->descripcion = $description[$i];
						$expense_detail->tipo_gasto = $type_expense[$i];
						$expense_detail->importe = $total_item[$i];
						$expense_detail->save();	
					}
				});
			} catch (Exception $e) {
				$save_expense = "Error al grabar el detalle de gastos";
			}
			return "OK";
		}
	}

	public function deleteExpense(){
		$expense = Input::get('data');
		$expenseJson = json_decode($expense);

		$expense_row = Expense::where('ruc',$expenseJson->ruc)->where('num_comprobante',$expenseJson->voucher_number)->firstOrFail();

		$delete_row = Expense::where('idgasto',$expense_row->idgasto)->delete();
		return "OK";
	}
}