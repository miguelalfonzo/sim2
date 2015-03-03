<?php

namespace Expense;

use \Auth;
use \BaseController;
use Dmkt\FondoInstitucional;
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
use \Client;
use \Dmkt\TypeRetention;
use \Log;
use \Common\Deposit;
use \BagoUser;
use \Exception;

class ExpenseController extends BaseController{

	private function getDay(){
		$lastDay = date('j/m/Y');
		$now=date('Y/m/j');
		$nuevafecha = strtotime('-7 day', strtotime($now));
		$toDay = date ('j/m/Y' , $nuevafecha);
		
		// $currentDate = getdate();
		// $toDay = $currentDate['mday']."/".str_pad($currentDate['mon'],2,'0',STR_PAD_LEFT)."/".$currentDate['year'];
		// $lastDay = '06/'.str_pad(($currentDate['mon']+1),2,'0',STR_PAD_LEFT).'/'.$currentDate['year'];
		$date = array(
			'toDay'		=> $toDay,
			'lastDay'	=> $lastDay
		);
		return $date;
	}

	private function getDayAll(){
		$lastDay = date('j/m/Y');
		$now=date('Y/m/j');
		$nuevafecha = strtotime('-2 month', strtotime($now));
		$toDay = date ('j/m/Y' , $nuevafecha);

		$date = array(
			'toDay'		=> $toDay,
			'lastDay'	=> $lastDay
		);
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

	public function show($token){
		$date         = $this->getDay();
		$typeProof    = ProofType::all();
		$typeExpense  = ExpenseType::orderBy('idtipogasto','asc')->get();
		$solicitude   = Solicitude::where('token',$token)->firstOrFail();
		$expense      = Expense::where('idsolicitud',$solicitude->idsolicitud)->get();
		$aproved_user = User::where('id',$solicitude->idaproved)->firstOrFail();
		if($aproved_user->type === 'S')
		{
			$name_aproved = $aproved_user->sup->nombres;
		}
		if($aproved_user->type === 'P')
		{
			$name_aproved = $aproved_user->gerprod->descripcion;
		}
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
			'solicitude'   => $solicitude,
			'typeProof'    => $typeProof,
			'typeExpense'  => $typeExpense,
			'date'         => $date,
			'balance'      => $balance,
			'expense'      => $expense,
			'name_aproved' => $name_aproved
		];
 		return View::make('Expense.register',$data);
	}
    public function showRegisterFondo($token){

        $date        = $this->getDay();
        $typeProof   = ProofType::all();
        $typeExpense = ExpenseType::orderBy('idtipogasto','asc')->get();
        $fondo       = FondoInstitucional::where('token',$token)->firstOrFail();
        $expense     = Expense::where('idfondo',$fondo->idfondo)->get();
        
        $balance     = $fondo->total;
        if(count($expense)>0)
        {
            $balance = 0;
            foreach ($expense as $key => $value) {
                $balance += $value->monto;
            }
            $balance= $fondo->total - $balance;
        }
        $data = [
            'fondo'       => $fondo,
            'typeProof'   => $typeProof,
            'typeExpense' => $typeExpense,
            'date'        => $date,
            'balance'     => $balance,
            'expense'     => $expense
        ];
        return View::make('Expense.register-fondo',$data);
    }

	public function showCont($token)
	{
		$date         = $this->getDayAll();
		$typeProof    = ProofType::all();
		$typeExpense  = ExpenseType::orderBy('idtipogasto','asc')->get();
		$solicitude   = Solicitude::where('token',$token)->firstOrFail();
		$expense      = Expense::where('idsolicitud',$solicitude->idsolicitud)->get();
		$aproved_user = User::where('id',$solicitude->idaproved)->firstOrFail();
		if($aproved_user->type === 'S')
		{
			$name_aproved = $aproved_user->sup->nombres;
		}
		if($aproved_user->type === 'P')
		{
			$name_aproved = $aproved_user->gerprod->descripcion;
		}
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
			'solicitude'   => $solicitude,
			'typeProof'    => $typeProof,
			'typeExpense'  => $typeExpense,
			'date'         => $date,
			'balance'      => $balance,
			'expense'      => $expense,
			'name_aproved' => $name_aproved
		];
 		return View::make('Expense.register_cont',$data);
	}

	public function registerExpense(){

		$result = array(); 
		$inputs = Input::all();

        if($inputs['type']=='S')
			$row_solicitude = Solicitude::where('token',$inputs['token'])->firstOrFail();
        if($inputs['type']=='F')
        	$row_fondo = $inputs['idfondo'];

    	$resultCode = null;

    	if($inputs['ruc'] != null && $inputs['number_prefix'] != null && $inputs['number_serie'] != null && $inputs['proof_type'] == DOCUMENTO_NO_SUSTENTABLE_ID){
    		$row_expense    = Expense::where('ruc',$inputs['ruc'])->where('num_prefijo',$inputs['number_prefix'])
						  ->where('num_serie',$inputs['number_serie'])->get();
		
			if(count($row_expense)>0)
			{
				$resultCode = -1;
			}
		}
		if($resultCode == -1)
			return array(
				'code' 	=> $resultCode,
				'error' => '',
				'msg'	=> ''
			);
		else
		{
			$expense = new Expense;
			
			$expense->num_prefijo = $inputs['number_prefix'];
			$expense->num_serie = $inputs['number_serie'];
			$expense->ruc = $inputs['ruc'];
			$expense->razon = $inputs['razon'];
			$expense->monto = $inputs['total_expense'];
            if($inputs['type']=='S')
                $expense->tipo = 'S';
            if($inputs['type']=='F')
                $expense->tipo = 'F';

			if($inputs['proof_type'] == '1' || $inputs['proof_type'] == '4' || $inputs['proof_type'] == '6')
			{
				$expense->igv = $inputs['igv'];
				$expense->imp_serv = $inputs['imp_service'];
				$expense->sub_tot = $inputs['sub_total_expense'];
				$expense->idigv = $expense->lastIdIgv()+1;
			}
			else
			{
				$expense->igv = null;
				$expense->imp_serv = null;
				$expense->sub_tot = null;	
				$expense->idigv = null;
			}
			$date = $inputs['date_movement'];
	        list($d, $m, $y) = explode('/', $date);
	        $d = mktime(11, 14, 54, $m, $d, $y);
	        $date = date("Y/m/d", $d);
	        $expense->descripcion = $inputs['desc_expense'];
	        $expense->fecha_movimiento = $date;
	        $expense->idcomprobante = $inputs['proof_type'];
			$idgasto = $expense->lastId()+1;
			$expense->idgasto = $idgasto;
            if($inputs['type']=='S')
			$expense->idsolicitud = $row_solicitude->idsolicitud;
            if($inputs['type']=='F')
            $expense->idfondo = $row_fondo;
			//Detail Expense
			$quantity = $inputs['quantity'];
			$description = $inputs['description'];
			// $type_expense = $expenseJson->type_expense;
			$total_item = $inputs['total_item'];
			$result_save = $expense->save();
			
			if($result_save)
			{
				try {
					DB::transaction (function() use ($idgasto,$quantity,$description,$total_item){
						for($i=0;$i<count($quantity);$i++)
						{
							$expense_detail = new ExpenseItem;
							$expense_detail->idgasto = $idgasto;
							$expense_detail->cantidad = $quantity[$i];
							$expense_detail->descripcion = $description[$i];
							// $expense_detail->tipo_gasto = $type_expense[$i];
							$expense_detail->importe = $total_item[$i];
							$expense_detail->save();	
						}
					});
				} catch (Exception $e) {
					return array(
						'code'	=> 0,
						'error'	=> '',
						'msg' 	=> '',
					);
				}
				return array(
					'code'		=> 1,
					'gastoId'	=> $expense->idgasto
				);
			}
			
		}
		
	}

	public function deleteExpense(){
		$inputs 	= Input::all();
		$gastoId 	= $inputs["gastoId"];
		$delete_row = Expense::find($gastoId)->delete();
		
		//$voucher_number = explode("-",$inputs['voucher_number']);
		
		//$expense_row 	= Expense::where('ruc',$inputs['ruc'])->where('num_prefijo',$voucher_number[0])
		//				  ->where('num_serie',$voucher_number[1])->firstOrFail();
		//$delete_row     = Expense::where('idgasto',$expense_row->gatoId)->delete();
		return "OK";
	}

	public function editExpense(){
		$expense        = Input::get('data');
		$expenseJson    = json_decode($expense);
		$voucher_number = explode("-",$expenseJson->voucher_number);
		$idExpense      = Expense::where('ruc',$expenseJson->ruc)->where('num_prefijo',$voucher_number[0])
						  ->where('num_serie',$voucher_number[1])->firstOrFail();
		$data           = ExpenseItem::where('idgasto','=',intval($idExpense->idgasto))->get();
		$response = ['data'=>$data, 'expense'=>$idExpense, 'date'=>$idExpense->fecha_movimiento];
		return $response;
	}

	public function updateExpense(){
		$inputs = Input::all();
		$voucher_number = explode("-",$inputs['voucher_number']);
		$expenseUpdate  = Expense::where('ruc',$inputs['ruc'])->where('num_prefijo',$voucher_number[0])
						  ->where('num_serie',$voucher_number[1])->get();
		if(count($expenseUpdate)>0)
		{
			foreach ($expenseUpdate as $key => $value) {
				$idgasto = $value->idgasto;
			}
			$expenseEdit = Expense::where('idgasto',$idgasto);
			$expenseEdit->num_prefijo = $inputs['number_prefix'];
			$expenseEdit->num_serie = $inputs['number_serie'];
			$expenseEdit->ruc = $inputs['ruc'];
			$expenseEdit->razon = $inputs['razon'];
			$expenseEdit->monto = $inputs['total_expense'];
            if($inputs['proof_type'] == '1' || $inputs['proof_type'] == '4' || $inputs['proof_type'] == '6')
            {
                if(isset($inputs['igv']) || isset($inputs['imp_service']))
                {
                    $expenseEdit->igv = $inputs['igv'];
                    $expenseEdit->imp_serv = $inputs['imp_service'];
                    $expenseEdit->sub_tot = $inputs['sub_total_expense'];
                }
                else
                {
                    $expenseEdit->sub_tot = $inputs['total_expense'];
                }
            }
            else
            {
                $expenseEdit->igv      = null;
                $expenseEdit->imp_serv = null;
                $expenseEdit->sub_tot  = null;
            }

			$expenseEdit->idcomprobante = $inputs['proof_type'];
			$date = $inputs['date_movement'];
	        list($d, $m, $y) = explode('/', $date);
	        $d = mktime(11, 14, 54, $m, $d, $y);
	        $date = date("Y/m/d", $d);
	        $expenseEdit->fecha_movimiento = $date;
	        $expenseEdit->descripcion = $inputs['desc_expense'];
	        if(isset($inputs['rep']))
			{
				if (is_numeric($inputs['rep']))
				{
					$expenseEdit->reparo = $inputs['rep'];
				}
			}
	        $data = $this->objectToArray($expenseEdit);
	        //Detail Expense
			$quantity = $inputs['quantity'];
			$description = $inputs['description'];
			
			// $type_expense = $expenseJson->type_expense;
			
			$total_item = $inputs['total_item'];

			if($expenseEdit->update($data))
			{
				$expense_detail_edit = ExpenseItem::where('idgasto',$idgasto)->delete();
				try {
					DB::transaction (function() use ($idgasto,$quantity,$description,$total_item){
						for($i=0;$i<count($quantity);$i++)
						{
							$expense_detail = new ExpenseItem;
							$expense_detail->idgasto = $idgasto;
							$expense_detail->cantidad = $quantity[$i];
							$expense_detail->descripcion = $description[$i];
							// $expense_detail->tipo_gasto = $type_expense[$i];
							$expense_detail->importe = $total_item[$i];
							$expense_detail->save();	
						}
					});
				} catch (Exception $e) {
					Log::error($e);
					return 0;
				}
				return 1;
			}
		}
	}

	// IDKC: CHANGE STATUS => REGISTRADO
	public function finishExpense($token){
		Log::error('finishExpense');

		$oldOolicitude      = Solicitude::where('token',$token)->first();
        $oldStatus          = $oldOolicitude->estado;
        $idSol              = $oldOolicitude->idsolicitud;

		$solicitude  = Solicitude::where('token',$token)->update(array('estado'=>REGISTRADO));
		if(count($solicitude) == 1)
		{
			$this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, REGISTRADO, Auth::user()->id, USER_CONTABILIDAD, $idSol);
			$states = State::orderBy('idestado', 'ASC')->get();
        	return Redirect::to('show_rm')->with('states', $states);
		}
	}

    public function finishExpenseFondo($idfondo){
        $fondo = FondoInstitucional::where('idfondo',$idfondo)->update(array('registrado'=>1));
        $states = State::orderBy('idestado', 'ASC')->get();
        return Redirect::to('show_rm')->with('states', $states);
    }
	public function viewExpense($token){
		$solicitude = Solicitude::where('token',$token)->firstOrFail();
		if(count($solicitude)>0)
		{
			$expenses = Expense::where('idsolicitud',$solicitude->idsolicitud)->get();
		}
		$gasto_total = 0;
		foreach ($expenses as $expense)
		{
			$gasto_total += $expense->monto;
		}

		$data = [
			'solicitude' => $solicitude,
			'expenses'    => $expenses,
			'total'      => $gasto_total
		];
		return View::make('Expense.view',$data);
	}
    public function viewExpenseFondo($token){

        $fondo = FondoInstitucional::where('token',$token)->firstOrFail();
        if(count($fondo)>0)
        {
            $expense = Expense::where('idfondo',$fondo->idfondo)->get();
        }
        $data = [
            'fondo' => $fondo,
            'expense'    => $expense
        ];
        return View::make('Expense.view-fondo',$data);
    }

	public function reportExpense($token){
		$solicitude = Solicitude::where('token',$token)->firstOrFail();
		$created_by = '';
		if($solicitude->user->type == 'R')
		{
			$created_by = $solicitude->rm->nombres.' '.$solicitude->rm->apellidos;
		}
		else if ($solicitude->user->type == 'S')
		{
			$created_by = $solicitude->sup->nombres.' '.$solicitude->sup->apellidos;
		}
		else
		{
			$created_by = 'Usuario no Autorizado';
		}
		$dni = new BagoUser;
		$dni = $dni->dni($solicitude->user->username);
		if ($dni['Status'] == 'Ok')
		{
			$dni = $dni['Data'];
		}
		else
		{
			$dni = $dni['Status'].' : '.$dni['Description'];
		}
		$expenses = Expense::where('idsolicitud',$solicitude->idsolicitud)->get();
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
		$total = 0;
		foreach($expenses as $expense)
		{
			$total += $expense->monto;
		}

		$data = array(
			'solicitude' => $solicitude,
			'date'       => $this->getDay(),
			'name'       => $name_aproved,
			'dni' 		 => $dni,
			'created_by' => $created_by,
			'charge'     => $charge,
			'expenses'   => $expenses,
			'total'      => $total
		);
		$html = View::make('Expense.report',$data)->render();
		return PDF::load($html, 'A4', 'landscape')->show();
	}
    public function reportExpenseFondo($token){

        $fondo = FondoInstitucional::where('token',$token)->firstOrFail();
        $expense = Expense::where('idfondo',$fondo->idfondo)->get();
        $data = array(
            'fondo'    => $fondo,
            'date'     => $this->getDay(),
            'expense'  => $expense
        );
        $html = View::make('Expense.report-fondo',$data);
        return PDF::load($html, 'A4', 'landscape')->show();


    }

	// public function test(){
 // 		// 	$html = View::make('Expense.report');
	// 	// return PDF::load($html, 'A4', 'landscape')->show();
	// 	// $solicituds = Solicitude::where('estado', '=', APROBADO)->where('asiento','=',1)->get();
	// 	// echo json_encode($solicituds);die;
	// 		$data = TypeRetention::all();
	// 		$data = "↵												LUCY ALFARO asdasdadasdad											";
	// 		echo trim($data,"↵");
	// }

	public function manageDocument()
	{
		try
		{
			DB::beginTransaction();
			$data = array();
			$now = getdate();
			$dateNow = $now['year'].'-'.$now['mon'].'-'.$now['mday'].' '.$now['hours'].':'.$now['minutes'].':'.$now['seconds'];
			$input = Input::all();
			
			if ($input['type'] == 'Update')
			{
				$document = ProofType::where('idcomprobante',$input['pk'])->first();
				$document->descripcion = strtoupper($input['desc']);
				$document->marca = strtoupper($input['marca']);
				$document->igv = $input['igv'];
				$document->save();
			}
			else
			{
				$document = new ProofType;
				$document->idcomprobante = $document->lastId()+1;
				$document->descripcion = strtoupper($input['desc']);
				$document->cta_sunat = $input['sunat'];
				$document->marca = strtoupper($input['marca']);
				$document->igv = $input['igv'];
				$document->save();
			}
			$data["Status"] = "Ok";				
			DB::commit();
		}
		catch (Exception $e)
		{
			Log::error($e);
			$data["Status"] = "Error";	
			DB::rollback();
		}
		return $data;
	}
}