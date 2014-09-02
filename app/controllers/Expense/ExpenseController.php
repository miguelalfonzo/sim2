<?php

namespace Expense;

use \BaseController;
use \View;
use \Dmkt\Activity;
use \Dmkt\Solicitude;
use \Expense\Expense;
use \Expense\ProofType;
use \Expense\ExpenseType;
use \Expense\ExpenseItem;
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
		$date = $this->getDay();
		$typeExpense = ExpenseType::orderBy('idtipogasto','asc')->get();

		$data = [
			'solicitude' => $solicitude,
			'typeProof' => $typeProof,
			'typeExpense' => $typeExpense,
			'date' => $date
		];

		$expense = Expense::where('idsolicitud',$solicitude->idsolicitud)->get();

		$balance = $solicitude->monto;
		if(count($expense)>0)
		{
			$data['expense'] = $expense;
			$balance = 0;
			foreach ($expense as $key => $value) {
				$balance += $value->monto;
			}
			$balance= $solicitude->monto - $balance;
 		}
 		
 		$data['balance'] = $balance;

		return View::make('Expense.register',$data);
	}

	public function registerExpense(){

		$expense = Input::get('data');
		$expenseJson = json_decode($expense);

		// var_dump($expenseJson->quantity);die;

		$row_solicitude = Solicitude::find($expenseJson->idsolicitude);

		$expense = new Expense;
		//Header Expense
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
		$date = $expenseJson->date_movement;
        list($d, $m, $y) = explode('/', $date);
        $d = mktime(11, 14, 54, $m, $d, $y);
        $date = date("Y/m/d", $d);
        $expense->fecha_movimiento = $date;
        $expense->tipo_comprobante = $expenseJson->proof_type;
		$idgasto = $expense->lastId()+1;
		$expense->idgasto = $idgasto;
		$expense->idsolicitud = $row_solicitude->idsolicitud;

		//Detail Expense
		$quantity = $expenseJson->quantity;
		$description = $expenseJson->description;
		$type_expense = $expenseJson->type_expense;
		$total_item = $expenseJson->total_item;

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

	public function editExpense(){
		$expense = Input::get('data');
		$expenseJson = json_decode($expense);

		$idExpense = Expense::where('ruc',$expenseJson->ruc)->where('num_comprobante',$expenseJson->number_voucher)->firstOrFail();
		
		$data = ExpenseItem::where('idgasto',$idExpense->idsolicitud)->get();

		return json_decode($data);
	}

	public function test(){
 		
 		$expense = Expense::get();
 		var_dump($expense);
 		echo json_decode($expense);

	}

}