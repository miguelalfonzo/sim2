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
use \User;
use \Common\State;
use \Input;
use \DB;
use \Redirect;
use \PDF;
use \Dmkt\Client;

class ExpenseController extends BaseController{

	private function getDay(){
		$currentDate = getdate();
		$toDay = $currentDate['mday']."/".str_pad($currentDate['mon'],2,'0',STR_PAD_LEFT)."/".$currentDate['year'];
		$lastDay = '06/'.str_pad(($currentDate['mon']+1),2,'0',STR_PAD_LEFT).'/'.$currentDate['year'];
		$date = ['toDay'=>$toDay,'lastDay'=> $lastDay];
		return $date;
	}

	private function objectToArray($object)
    {
        $array = array();
        foreach ($object as $member => $data) {
            $array[$member] = $data;
        }
        return $array;
    }

	public function show(){
		$date        = $this->getDay();
		$typeProof   = ProofType::all();
		$typeExpense = ExpenseType::orderBy('idtipogasto','asc')->get();
		$token       = Input::get('token');
		$solicitude  = Solicitude::where('token',$token)->firstOrFail();
		$expense     = Expense::where('idsolicitud',$solicitude->idsolicitud)->get();
		$balance     = $solicitude->monto;
		if(count($expense)>0)
		{
			$balance         = 0;
			foreach ($expense as $key => $value) {
				$balance += $value->monto;
			}
			$balance= $solicitude->monto - $balance;
 		}
 		$data = [
			'solicitude'  => $solicitude,
			'typeProof'   => $typeProof,
			'typeExpense' => $typeExpense,
			'date'        => $date,
			'balance'     => $balance,
			'expense'     => $expense
		];
 		return View::make('Expense.register',$data);
	}

	public function registerExpense(){
		$expense        = Input::get('data');
		$expenseJson    = json_decode($expense);
		$row_solicitude = Solicitude::where('token',$expenseJson->token)->firstOrFail();
		$row_expense    = Expense::where('num_comprobante',$expenseJson->voucher_number)->where('ruc',$expenseJson->ruc)->get();

		if(count($row_expense)>0)
		{
			return -1;
		}
		else
		{
			$expense = new Expense;
			//Header Expense
			$expense->idresponse = 1;
			$expense->num_comprobante = $expenseJson->voucher_number;
			$expense->ruc = $expenseJson->ruc;
			$expense->razon = $expenseJson->razon;
			$expense->monto = $expenseJson->total_expense;
			if($expenseJson->proof_type == '2')
			{
				$expense->igv = $expenseJson->igv;
				$expense->imp_serv = $expenseJson->imp_service;
				$expense->sub_tot = $expenseJson->sub_total_expense;
			}
			else
			{
				$expense->igv = null;
				$expense->imp_serv = null;
				$expense->sub_tot = null;	
			}
			$date = $expenseJson->date_movement;
	        list($d, $m, $y) = explode('/', $date);
	        $d = mktime(11, 14, 54, $m, $d, $y);
	        $date = date("Y/m/d", $d);
	        $expense->descripcion = $expenseJson->desc_expense;
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
							$expense_detail = new ExpenseItem;
							$expense_detail->idgasto = $idgasto;
							$expense_detail->cantidad = $quantity[$i];
							$expense_detail->descripcion = $description[$i];
							$expense_detail->tipo_gasto = $type_expense[$i];
							$expense_detail->importe = $total_item[$i];
							$expense_detail->save();	
						}
					});
				} catch (Exception $e) {
					return 0;
				}
				return 1;
			}
		}
	}

	public function deleteExpense(){
		$expense     = Input::get('data');
		$expenseJson = json_decode($expense);
		$expense_row = Expense::where('ruc',$expenseJson->ruc)->where('num_comprobante',$expenseJson->voucher_number)->firstOrFail();
		$delete_row  = Expense::where('idgasto',$expense_row->idgasto)->delete();
		return "OK";
	}

	public function editExpense(){
		$expense     = Input::get('data');
		$expenseJson = json_decode($expense);
		$idExpense   = Expense::where('ruc',$expenseJson->ruc)->where('num_comprobante',$expenseJson->voucher_number)->firstOrFail();
		$data        = ExpenseItem::where('idgasto','=',intval($idExpense->idgasto))->get();
		$response = ['data'=>$data, 'expense'=>$idExpense, 'date'=>$idExpense->fecha_movimiento];
		return $response;
	}

	public function updateExpense(){
		$expense       = Input::get('data');
		$expenseJson   = json_decode($expense);
		$expenseUpdate = Expense::where('ruc',$expenseJson->ruc)->where('num_comprobante',$expenseJson->voucher_number)->get();
		if(count($expenseUpdate)>0)
		{
			foreach ($expenseUpdate as $key => $value) {
				$idgasto = $value->idgasto;
			}
			$expenseEdit = Expense::where('idgasto',$idgasto);
			$expenseEdit->num_comprobante = $expenseJson->voucher_number;
			$expenseEdit->ruc = $expenseJson->ruc;
			$expenseEdit->razon = $expenseJson->razon;
			$expenseEdit->monto = $expenseJson->total_expense;
			if($expenseJson->proof_type == '2')
			{
				$expenseEdit->igv = $expenseJson->igv;
				$expenseEdit->imp_serv = $expenseJson->imp_service;
				$expenseEdit->sub_tot = $expenseJson->sub_total_expense;
			}
			else
			{
				$expenseEdit->igv = null;
				$expenseEdit->imp_serv = null;
				$expenseEdit->sub_tot = null;	
			}
			$expenseEdit->tipo_comprobante = $expenseJson->proof_type;
			$date = $expenseJson->date_movement;
	        list($d, $m, $y) = explode('/', $date);
	        $d = mktime(11, 14, 54, $m, $d, $y);
	        $date = date("Y/m/d", $d);
	        $expenseEdit->fecha_movimiento = $date;
	        $data = $this->objectToArray($expenseEdit);
	        //Detail Expense
			$quantity = $expenseJson->quantity;
			$description = $expenseJson->description;
			$type_expense = $expenseJson->type_expense;
			$total_item = $expenseJson->total_item;
			if($expenseEdit->update($data))
			{
				$expense_detail_edit = ExpenseItem::where('idgasto',$idgasto)->delete();
				try {
					DB::transaction (function() use ($idgasto,$quantity,$description,$type_expense,$total_item){
						for($i=0;$i<count($quantity);$i++)
						{
							$expense_detail = new ExpenseItem;
							$expense_detail->idgasto = $idgasto;
							$expense_detail->cantidad = $quantity[$i];
							$expense_detail->descripcion = $description[$i];
							$expense_detail->tipo_gasto = $type_expense[$i];
							$expense_detail->importe = $total_item[$i];
							$expense_detail->save();	
						}
					});
				} catch (Exception $e) {
					return 0;
				}
				return 1;
			}
		}
	}

	public function finishExpense($token){
		$solicitude  = Solicitude::where('token',$token)->update(array('estado'=>REGISTRADO));
		if(count($solicitude) == 1)
		{
			$states = State::orderBy('idestado', 'ASC')->get();
        	return Redirect::to('show_rm')->with('states', $states);
		}
	}

	public function viewExpense($token){
		$solicitude = Solicitude::where('token',$token)->firstOrFail();
		if(count($solicitude)>0)
		{
			$expense = Expense::where('idsolicitud',$solicitude->idsolicitud)->get();
		}
		$data = [
			'solicitude' => $solicitude,
			'expense' => $expense
		];
		return View::make('Expense.view',$data);
	}

	public function reportExpense($token){
		$solicitude = Solicitude::where('token',$token)->firstOrFail();
		$expense = Expense::where('idsolicitud',$solicitude->idsolicitud)->get();
		$aproved_user = User::where('id',$solicitude->idaproved)->firstOrFail();
		if($aproved_user->type === 'P')
		{
			$name_aproved = $aproved_user->gerprod->descripcion;
			$charge = "Gerente de Producto";
		}
		if($aproved_user->type === 'S')
		{
			$name_aproved = $aproved_user->sup->nombres;
			$charge = "Supervisor";
		}
		$data = [
			'solicitude' => $solicitude,
			'date' => $this->getDay(),
			'name' => $name_aproved,
			'charge' => $charge,
			'expense' => $expense
		];
		$html = View::make('Expense.report',$data);
		return PDF::load($html, 'A4', 'landscape')->show();
	}

	public function test(){
 		$html = View::make('Expense.report');
		return PDF::load($html, 'A4', 'landscape')->show();
	}
}