<?php

namespace Expense;

use \Auth;
use \BaseController;
use \View;
use \Dmkt\Activity;
use \Dmkt\Solicitude;
use \User;
use \Common\State;
use \Input;
use \DB;
use \Redirect;
use \PDF;
use \Client;
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

	public function registerExpense()
	{
		try
		{
			DB::beginTransaction();
			$result = array(); 
			$inputs = Input::all();
	        $solicitude = Solicitude::where( 'token' , $inputs['token'] )->first();
 
	    	$resultCode = null;
	    	$proof = ProofType::find( $inputs['proof_type']);

	    	if ( $proof->code != 'N' )
		    	if($inputs['ruc'] != null && $inputs['number_prefix'] != null && $inputs['number_serie'] != null )
		    	{
		    		$row_expense    = Expense::where('ruc',$inputs['ruc'])->where('num_prefijo',$inputs['number_prefix'])->where('num_serie',$inputs['number_serie'])->get();	
					if(count($row_expense)>0)
						$resultCode = -1;
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
	            if( $proof->igv == 1 )
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
				$total_item = $inputs['total_item'];
				if( isset( $inputs['rep'] ) )
					$expense->reparo = $inputs['rep'];
				
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
						$expense_detail->tipo_gasto = $inputs['tipo_gasto'][$i];
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

	public function deleteExpense()
	{
		try
		{
			DB::beginTransaction();
			$inputs 	= Input::all();
			$expense = Expense::find( $inputs['gastoId'] );
			if ( count( $expense ) == 0 )
				return $this->warninException( __FUNCTION__ , 'Gasto no encontrado con Id: '.$inputs['gastoId'] );
			else
				if ( !$expense->delete() )
				{
					DB::rollback();
					return $this->warninException( __FUNCTION__ , 'No se pudo eliminar el gasto' );
				}
				else
				{
					DB::commit();
					return $this->setRpta();
				} 
		}
		catch( Exception $e )
		{
			DB::rollback();
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	public function editExpense()
	{
		try
		{
			$inputs = Input::all();
			$idExpense      = Expense::find( $inputs['idgasto']);
			$data           = $idExpense->items;
			$response = ['data'=>$data, 'expense'=>$idExpense, 'date'=>$idExpense->fecha_movimiento];
			return $response;
		}
		catch ( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	public function updateExpense()
	{
		try
		{
			DB::beginTransaction();
			$inputs = Input::all();
			$expenseEdit  = Expense::find($inputs['idgasto']);
			if( count( $expenseEdit ) > 0 )
			{
				$expenseEdit->num_prefijo   = $inputs['number_prefix'];
				$expenseEdit->num_serie 	= $inputs['number_serie'];
				$expenseEdit->ruc			= $inputs['ruc'];
				$expenseEdit->razon 		= $inputs['razon'];
				$expenseEdit->monto 		= $inputs['total_expense'];

				$proof_type = ProofType::find( $inputs['proof_type'] );
	            if ( $proof_type->igv == 1 )
	                if ( isset( $inputs['igv'] ) && isset( $inputs['imp_service'] ) )
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

	            if ( isset( $inputs['idregimen']))
		            if ( $inputs['idregimen'] == 0 )
		            {
		            	$expenseEdit->idtipotributo = null;
		            	$expenseEdit->monto_tributo = null;
		            }
		            elseif ( $inputs['idregimen'] >= 1 )
		            {
	            		$expenseEdit->idtipotributo = $inputs['idregimen'];
		            	$expenseEdit->monto_tributo = $inputs['monto_regimen'];    	
		            }

				$expenseEdit->idcomprobante = $inputs['proof_type'];
				$date = $inputs['date_movement'];
		        list($d, $m, $y) = explode('/', $date);
		        $d = mktime(11, 14, 54, $m, $d, $y);
		        $date = date("Y/m/d", $d);
		        $expenseEdit->fecha_movimiento = $date;
		        $expenseEdit->descripcion = $inputs['desc_expense'];
		        
		        if( isset( $inputs['rep'] ) )
					$expenseEdit->reparo = $inputs['rep'];
				
		        $quantity = $inputs['quantity'];
				$description = $inputs['description'];
				
				$total_item = $inputs['total_item'];

				if( $expenseEdit->save() )
				{
					ExpenseItem::where( 'idgasto' , $expenseEdit->id )->delete();
					for($i=0;$i<count($quantity);$i++)
					{
						$expense_detail = new ExpenseItem;
						$expense_detail->id = $expense_detail->lastId() + 1;
						$expense_detail->tipo_gasto = $inputs['tipo_gasto'][$i];
						$expense_detail->idgasto = $expenseEdit->id;
						$expense_detail->cantidad = $quantity[$i];
						$expense_detail->descripcion = $description[$i];
						$expense_detail->importe = $total_item[$i];
						if ( !$expense_detail->save() )
						{
							DB::rollback();	
							return 0;	
						}
					}
					DB::commit();
					return 1;
				}
			}
		}
		catch ( Exception $e )
		{
			DB::rollback();
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	// IDKC: CHANGE STATUS => REGISTRADO
	public function finishExpense()
	{
		try
		{
			DB::beginTransaction();
			$inputs = Input::all();
			$token = $inputs['token'];
			$solicitud  = Solicitude::where('token',$token)->first();
			if( count($solicitud) == 0 )
				return $this->warninException( __FUNCTION__ , 'No se encontro la solicitud');
			else
			{
				$oldIdEstado = $solicitud->idestado;
				$solicitud->idestado = REGISTRADO;
				if ( !$solicitud->save() )
					return $this->warninException( __FUNCTION__ , 'No se pudo procesar la solicitud');
				else
				{
					$rpta = $this->setStatus( $oldIdEstado, REGISTRADO, Auth::user()->id, USER_CONTABILIDAD, $solicitud->id );
					if ( $rpta[status] == ok )
					{
						Session::put( 'state' , R_GASTO );
						DB::commit();
						return $rpta;
					}
				}	
			}
			DB::rollback();
			return $rpta;
		}
		catch (Exception $e)
		{
			DB::rollback();
			return $this->internalException($e,__FUNCTION__);
		}
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
				$document = ProofType::where('id',$input['pk'])->first();
				$document->descripcion = strtoupper($input['desc']);
				$document->marca = strtoupper($input['marca']);
				$document->igv = $input['igv'];
			}
			else
			{
				$document = new ProofType;
				$document->id = $document->lastId() + 1;
				$document->descripcion = strtoupper($input['desc']);
				$document->cta_sunat = $input['sunat'];
				$document->marca = strtoupper($input['marca']);
				$document->igv = $input['igv'];
			}
			if ( !$document->save() )
				return $this->warninException( __FUNCTION__ , 'No se pudo procesar el documento');
			else
			{
				DB::commit();
				return $this->setRpta();
			}
		}
		catch (Exception $e)
		{
			DB::rollback();
			return $this->internalException( $e , __FUNCTION__ );
		}
	}



	public function reportExpense($token){
		$solicitude = Solicitude::where('token',$token)->firstOrFail();
		$detalle = $solicitude->detalle;
		$jDetalle = json_decode( $solicitude->detalle->detalle );
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
		if($solicitude->createdBy->type == REP_MED )
			$created_by = $solicitude->rm->nombres.' '.$solicitude->rm->apellidos;
		else if ($solicitude->createdBy->type == SUP )
			$created_by = $solicitude->sup->nombres.' '.$solicitude->sup->apellidos;
		else
			$created_by = 'Usuario no Autorizado';
		$dni = new BagoUser;
		$dni = $dni->dni($solicitude->createdBy->username);
		if ($dni[status] == ok )
			$dni = $dni[data];
		else
			$dni = '';
		$expenses = $solicitude->expenses;
		$aproved_user = User::where('id',$solicitude->acceptHist->updated_by)->firstOrFail();
		if($aproved_user->type == GER_PROD )
		{
			$name_aproved = $aproved_user->gerprod->descripcion;
			$charge = "Gerente de Producto";
		}
		if($aproved_user->type == SUP )
		{
			$name_aproved = $aproved_user->sup->nombres;
			$charge = "Supervisor";
		}
		if ( $solicitude->detalle->idmoneda == DOLARES )
		{
			foreach( $expenses as $expense )
			{
				$eTc = ChangeRate::where('fecha' , $expense->fecha_movimiento )->first();
				if ( is_null( $eTc ) )
					$expense->tc = ChangeRate::getTc();
				else	
					$expense->tc = $eTc;
				Log::error( json_encode( $expense->tc ));
				$expense->monto = round ( $expense->monto * $expense->tc->compra , 2 , PHP_ROUND_HALF_DOWN );
			}
		}

		$total = $expenses->sum('monto');
		$data = array(
			'solicitude' => $solicitude,
			'detalle'	 => $jDetalle,
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
		$data['balance'] = $this->reportBalance( $solicitude , $detalle , $jDetalle , $total );
		$html = View::make('Expense.report',$data)->render();
		return PDF::load($html, 'A4', 'landscape')->show();
	}

	public function reportExpenseFondo($token)
	{
        $fondo = Solicitude::where('token',$token)->first();
        $detalle = $fondo->detalle;
        $jDetalle = json_decode( $detalle->detalle );
        $expense = $fondo->expenses;
        $data = array(
            'fondo'    => $fondo,
            'detalle'  => $jDetalle,
            'date'     => $this->getDay(),
            'expense'  => $expense,
        );
        $data['balance'] = $this->reportBalance( $fondo , $detalle , $jDetalle , $expense->sum('monto') );
        $html = View::make('Expense.report-fondo',$data)->render();
        return PDF::load($html, 'A4', 'landscape')->show();
    }

    private function reportBalance( $solicitud , $detalle , $jDetalle , $mGasto )
	{
		if ( is_null( $detalle->deposit ) )
			return array( 'bussiness' => '-' , 'employed' => '-' );
		else
		{
			$mDeposit = $detalle->deposit->total;
		    if ( $detalle->deposit->account->idtipomoneda == DOLARES )
	    		$mDeposit = $mDeposit * $jDetalle->tcv;
		    
		    if ( isset( $jDetalle->monto_retencion ) )
		    	$monto_retencion = $jDetalle->monto_retencion ;
		    else
		    	$monto_retencion = 0;
		    $mBalance = $mDeposit - ( $mGasto - $monto_retencion );  // - $monto_retencion );
		    if ( $mBalance > 0)
		    	return array( 'bussiness' => round( $mBalance , 2 , PHP_ROUND_HALF_DOWN ) , 'employed' => 0 );
		    elseif ( $mBalance < 0 )
		    	return array( 'bussiness' => 0 , 'employed' => round( $mBalance*-1 , 2 , PHP_ROUND_HALF_DOWN ) );
		    elseif ( $mBalance == 0 )
				return array( 'bussiness' => 0 , 'employed' => 0 );
			else
				return $this->warninException( __FUNCTION__ , 'No se pudo procesar el Balance( '.$mBalance.' )' );
		}
	}

	public function getExpenses()
	{
		try
		{
			$inputs = Input::all();
			$solicitud = Solicitude::find( $inputs['idsolicitud']);
			$data = array(
				'solicitud' => $solicitud,
				'expense'   => $solicitud->expenses
			);
			return $this->setRpta( View::make('Dmkt.Solicitud.Section.gasto-table')->with( $data )->render() );
		}
		catch ( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	public function getDocument()
	{
		try
		{
			$inputs = Input::all();
			$document = Expense::find( $inputs['id'] );
			if ( is_null( $document ) )
				return $this->warninException( __FUNCTION__ , 'No se encontro el documento con Id: '.$inputs['id'] );
			else
			{
				$document->moneda = $document->solicitud->detalle->typeMoney->simbolo ;
				return $this->setRpta( $document );
			}
		}
		catch ( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	public function updateDocument()
	{
		try 
		{
			$inputs = Input::all();
			$document = Expense::find( $inputs['id'] );
			if ( is_null( $document ) )
				return $this->warninException( __FUNCTION__ , 'No se encontro el documento con Id: '.$inputs['id'] );
			else
			{
				$regimenes = Regimen::lists( 'id' );
				if ( in_array( $inputs['idregimen'] , $regimenes ) )
				{
					$document->idtipotributo = $inputs['idregimen'];
					$document->monto_tributo = $inputs['monto'];
				}
				elseif ( $inputs['idregimen'] == 0 )
				{
					$document->idtipotributo = null;
					$document->monto_tributo = null;		
				}
				else
					return $this->warninException( __FUNCTION__ , 'No esta registrado la retencion o detracción con Id: '.$inputs['idregimen'] );
				if ( !$document->save() )
					return $this->warninException( __FUNCTION__ , 'No se pudo actualizar el documento' );
				else
					return $this->setRpta();
			}	
		} 
		catch ( Exception $e ) 
		{
			return $this->internalException( $e , __FUNCTION__ );	
		}
	}
}