<?php

namespace Expense;

use \Auth;
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
use \Client;
use \Dmkt\TypeRetention;
use \Log;
use \Common\Deposit;
use \BagoUser;
use \Exception;
use \Session;
use \Dmkt\Account;

class ExpenseController extends BaseController
{

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
		$typeExpense  = ExpenseType::orderBy('id','asc')->get();
		$solicitude   = Solicitude::where('token',$token)->first();
		$expense      = Expense::where('idsolicitud',$solicitude->idsolicitud)->get();
		$aproved_user = User::where('id',$solicitude->acceptHist->updated_by)->first();
		if($aproved_user->type === SUP )
		{
			$name_aproved = $aproved_user->sup->nombres;
		}
		if($aproved_user->type === GER_PROD )
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
			'solicitud'   => $solicitude,
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
        //$fondo       = FondoInstitucional::where('token',$token)->firstOrFail();
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

	public function registerExpense()
	{
		try
		{
			DB::beginTransaction();
			$result = array(); 
			$inputs = Input::all();
	        $solicitude = Solicitude::where( 'token' , $inputs['token'] )->first();
 
	    	$resultCode = null;

	    	if($inputs['ruc'] != null && $inputs['number_prefix'] != null && $inputs['number_serie'] != null && $inputs['proof_type'] == DOCUMENTO_NO_SUSTENTABLE_ID)
	    	{
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
	            
				if($inputs['proof_type'] == '1' || $inputs['proof_type'] == '4' || $inputs['proof_type'] == '6')
				{
					$expense->igv = $inputs['igv'];
					$expense->imp_serv = $inputs['imp_service'];
					$expense->sub_tot = $inputs['sub_total_expense'];
					$expense->idigv = $expense->lastIdIgv() + 1;
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
		 		$expense->idsolicitud = $solicitude->id;
				$expense->id = $expense->lastId() + 1;
				//Detail Expense
				$quantity = $inputs['quantity'];
				$description = $inputs['description'];
				// $type_expense = $expenseJson->type_expense;
				$total_item = $inputs['total_item'];
				$result_save = $expense->save();
				
				if($result_save)
				{
					for($i=0;$i<count($quantity);$i++)
					{
						$expense_detail = new ExpenseItem;
						$expense_detail->id = $expense_detail->lastId() + 1 ;
						$expense_detail->idgasto = $expense->id;
						$expense_detail->cantidad = $quantity[$i];
						$expense_detail->descripcion = $description[$i];
						// $expense_detail->tipo_gasto = $type_expense[$i];
						$expense_detail->importe = $total_item[$i];
						if ( !$expense_detail->save() )
						{
							DB::rollback();
							return array(
								'code'	=> 0,
								'error'	=> '',
								'msg' 	=> '',
							);
						}				
					}
					DB::commit();
					return array(
						'code'		=> 1,
						'gastoId'	=> $expense->idgasto
					);
				}
			}
		}
		catch ( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
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

	public function updateExpense()
	{
		try
		{
			DB::beginTransaction();
			$inputs = Input::all();
			$voucher_number = explode("-",$inputs['voucher_number']);
			$expenseUpdate  = Expense::where('ruc',$inputs['ruc'])->where('num_prefijo',$voucher_number[0])
							  ->where('num_serie',$voucher_number[1])->get();
			if(count($expenseUpdate)>0)
			{
				foreach ($expenseUpdate as $key => $value)
					$idgasto = $value->idgasto;

				$expenseEdit = Expense::where('idgasto',$idgasto);
				$expenseEdit->num_prefijo = $inputs['number_prefix'];
				$expenseEdit->num_serie = $inputs['number_serie'];
				$expenseEdit->ruc = $inputs['ruc'];
				$expenseEdit->razon = $inputs['razon'];
				$expenseEdit->monto = $inputs['total_expense'];

	            if($inputs['proof_type'] == '1' || $inputs['proof_type'] == '4' || $inputs['proof_type'] == '6')
	                if(isset($inputs['igv']) || isset($inputs['imp_service']))
	                {
	                    $expenseEdit->igv = $inputs['igv'];
	                    $expenseEdit->imp_serv = $inputs['imp_service'];
	                    $expenseEdit->sub_tot = $inputs['sub_total_expense'];
	                }
	                else
	                    $expenseEdit->sub_tot = $inputs['total_expense'];
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
					if (is_numeric($inputs['rep']))
						$expenseEdit->reparo = $inputs['rep'];
				
				$data = $this->objectToArray($expenseEdit);
		        //Detail Expense
				$quantity = $inputs['quantity'];
				$description = $inputs['description'];
				
				// $type_expense = $expenseJson->type_expense;
				
				$total_item = $inputs['total_item'];

				if($expenseEdit->update($data))
				{
					$expense_detail_edit = ExpenseItem::where('idgasto',$idgasto)->delete();
					for($i=0;$i<count($quantity);$i++)
					{
						$expense_detail = new ExpenseItem;
						$expense_detail->idgasto = $idgasto;
						$expense_detail->cantidad = $quantity[$i];
						$expense_detail->descripcion = $description[$i];
						// $expense_detail->tipo_gasto = $type_expense[$i];
						$expense_detail->importe = $total_item[$i];
						if ( !$expense_detail->save() )
							return 0;	
					}
					return 1;
				}
			}
		}
		catch ( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	// IDKC: CHANGE STATUS => REGISTRADO
	public function finishExpense($token)
	{
		try
		{
			DB::beginTransaction();
			$oldOolicitude      = Solicitude::where('token',$token)->first();
	        $oldStatus          = $oldOolicitude->estado;
	        $idSol              = $oldOolicitude->idsolicitud;
			$solicitude  = Solicitude::where('token',$token)->update(array('estado'=>REGISTRADO));
			if(count($solicitude) == 1)
			{
				$rpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, REGISTRADO, Auth::user()->id, USER_CONTABILIDAD, $idSol,SOLIC);
				if ( $rpta[status] == ok )
				{
					DB::commit();
				}		
			}
		}
		catch (Exception $e)
		{
			DB::rollback();
			$rpta = $this->internalException($e,__FUNCTION__);
		}
		Session::put('state',R_REVISADO);
		if ( Auth::user()->type == ASIS_GER )
			return Redirect::to('registrar-fondo');
		else
	    	return Redirect::to('show_user');
	}

    public function finishExpenseFondo($idfondo){
        //$fondo = FondoInstitucional::where('idfondo',$idfondo)->update(array('registrado'=>1));
        $states = State::orderBy('idestado', 'ASC')->get();
        return Redirect::to('show_user')->with('states', $states);
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
    public function showFondo($token)
    {
    	try
    	{
    		$solicitud = Solicitude::where( 'token' , $token )->first();
	        if( count( $solicitud ) == 0 )
	            return $this->warninException( __FUNCTION__ , 'No se encontro los datos de la Solicitud Institucional' );
	        else
	        	if ( $solicitud->idtiposolicitud == SOL_REP )
	        		return $this->warninException( __FUNCTION__ , 'La solicitud con Id: '.$solicitud->id.' no es Insitucional' );
	        	else
	        	{
			        $data['solicitud']	 = $solicitud;
			        $data['detalle'] = json_decode( $solicitud->detalle->detalle );
			        if ( Auth::user()->type == CONT && $solicitud->idestado == DEPOSITADO )
                    {
                        $data['date'] = $this->getDay();
                        $data['lv'] = $solicitud->titulo;
                    }
                    elseif ( Auth::user()->type == TESORERIA && $solicitud->idestado == DEPOSITO_HABILITADO )
                    	$data['banks'] = Account::banks();
			        return View::make('Dmkt.Solicitud.Institucional.view',$data);
			    }
	    }
	    catch (Exception $e)
	    {
	    	return $this->internalException($e,__FUNCTION__);
	    }
	}

	public function reportExpense($token){
		$solicitude = Solicitude::where('token',$token)->firstOrFail();
		$clientes   = array();
		$cmps = array();
		foreach($solicitude->clients as $client)
        {
            if ($client->from_table == TB_DOCTOR)
            {
                $doctors = $client->doctors;
                array_push( $clientes, $doctors->pefnombres.' '.$doctors->pefpaterno.' '.$doctors->pefmaterno );
            	array_push( $cmps , 'CMP: '.$doctors->pefnrodoc1);
            }
            elseif ($client->from_table == TB_INSTITUTE)
            {
                array_push( $clientes, $client->institutes->pejrazon);
                array_push( $cmps, 'Ruc: '.$client->institutes->pejnrodoc);       
            }
            else
            {
                array_push ( $clientes, 'No encontrado' );
        		array_push ( $cmps, 'No encontrado' );		
        	}
        }
        $clientes = implode(',',$clientes);
        $cmps = implode(',',$cmps);
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
			$dni = ' ________ ';
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
			'clientes'	 => $clientes,
			'cmps'		 => $cmps,
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

        //$fondo = FondoInstitucional::where('token',$token)->firstOrFail();
        $expense = Expense::where('idfondo',$fondo->idfondo)->get();
        $data = array(
            'fondo'    => $fondo,
            'date'     => $this->getDay(),
            'expense'  => $expense
        );
        $html = View::make('Expense.report-fondo',$data);
        return PDF::load($html, 'A4', 'landscape')->show();


    }

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


	// public function test(){
 // 		// 	$html = View::make('Expense.report');
	// 	// return PDF::load($html, 'A4', 'landscape')->show();
	// 	// $solicituds = Solicitude::where('estado', '=', APROBADO)->where('asiento','=',1)->get();
	// 	// echo json_encode($solicituds);die;
	// 		$data = TypeRetention::all();
	// 		$data = "↵												LUCY ALFARO asdasdadasdad											";
	// 		echo trim($data,"↵");
	// }
}