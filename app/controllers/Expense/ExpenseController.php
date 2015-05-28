<?php

namespace Expense;

use \Auth;
use \BaseController;
use \View;
use \Dmkt\Activity;
use \Dmkt\Solicitud;
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
use \Validator;

class ExpenseController extends BaseController
{

	private function objectToArray($object)
    {
        $array = array();
        foreach ($object as $member => $data) {
            $array[$member] = $data;
        }
        return $array;
    }

    private function validateInputExpense( $inputs )
    {
        $rules = array( 'token'    		 => 'required|string|size:40|exists:solicitud,token' ,
                        'proof_type'     => 'required|integer|min:1|exists:tipo_comprobante,id' ,
                        'date_movement'  => 'required|date_format:"d/m/Y"|after:'.date("Y-m-d"),
                        'desc_expense'   => 'required|string|min:1',
                        'tipo_gasto'	 => 'required|array|min:1|each:integer|each:min,1|each:exists,tipo_gasto,id',
                        'quantity'		 => 'required|array|min:1|each:integer|each:min,1',
                        'description'	 => 'required|array|min:1|each:string|each:min,1',
                        'total_item'	 => 'required|array|min:1|each:numeric|each:min,1',
                        'total_expense'  => 'required|numeric|min:1' );  

        $validator = Validator::make( $inputs, $rules );
        if ( $validator->fails() ) 
            return $this->warningException( substr( $this->msgValidator( $validator ) , 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
        else
        {
        	$proofType = ProofType::find( $inputs['proof_type'] );
        	$validator->sometimes( [ 'number_prefix' , 'number_serie' ] , 'required|numeric|min:1' , function( $input ) use( $proofType )
            {
                return $proofType->marca != 'N';
            });
            $validator->sometimes( 'ruc' , 'required|numeric|digits:11' , function( $input ) use( $proofType )
            {
                return $proofType->marca != 'N';
            });
            $validator->sometimes( 'razon' , 'required|string|min:1' , function( $input ) use( $proofType )
            {
                return $proofType->marca != 'N';
            });
            $validator->sometimes( 'imp_service' , 'numeric|min:0' , function( $input ) use( $proofType )
            {
                return $proofType->igv == 1 ;
            });
            $validator->sometimes( 'igv' , 'numeric|min:0' , function( $input ) use( $proofType )
            {
                return $proofType->igv == 1 ;
            });
            if ( $validator->fails() ) 
        	    return $this->warningException( substr( $this->msgValidator( $validator ) , 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
        	else
	    	    return $this->setRpta();
        } 
    }

	public function registerExpense()
	{
		try
		{
			//$result = array(); 
			$inputs = Input::all();
			$middleRpta = $this->validateInputExpense( $inputs );
			if ( $middleRpta[ status ] === ok )
	        {
		        $solicitud = Solicitud::where( 'token' , $inputs['token'] )->first();
	 
		    	$proof = ProofType::find( $inputs['proof_type']);

		    	if ( $proof->code != 'N' )
			    	if( !is_null( $inputs['ruc'] ) && ! is_null( $inputs['number_prefix'] ) && $inputs['number_serie'] != null )
			    	{
			    		$row_expense    = Expense::where( 'ruc' , $inputs['ruc'] )->where( 'num_prefijo' ,$inputs[ 'number_prefix' ] )->where( 'num_serie' ,$inputs['number_serie'])->get();	
						if( $row_expense->count() > 0 )
							return $this->warningException( 'Ya existe un gasto registrado con Ruc: '.$inputs['ruc'] . ' numero: '.$inputs['number_prefix'].'-'.$inputs['num_serie'] , __FUNCTION__ , __LINE__ , __FILE__ );
					}

				DB::beginTransaction();
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

				$date = $inputs['date_movement'];
		        list($d, $m, $y) = explode('/', $date);
		        $d = mktime(11, 14, 54, $m, $d, $y);
		        
		        $expense->descripcion = $inputs['desc_expense'];
		        $expense->fecha_movimiento = date("Y/m/d", $d);
		        $expense->idcomprobante = $inputs['proof_type'];
		 		$expense->id_solicitud = $solicitud->id;
				$expense->id = $expense->lastId() + 1;
				if( isset( $inputs['rep'] ) )
					$expense->reparo = $inputs['rep'];
				$expense->save();

				//Detail Expense
			
				for($i=0;$i<count( $inputs['quantity'] );$i++)
				{
					$expense_detail = new ExpenseItem;
					$expense_detail->id = $expense_detail->lastId() + 1 ;
					$expense_detail->id_gasto = $expense->id;
					$expense_detail->cantidad = $inputs['quantity'][$i];
					$expense_detail->descripcion = $inputs['description'][$i];
					$expense_detail->tipo_gasto = $inputs['tipo_gasto'][$i];
					$expense_detail->importe = $inputs['total_item'][$i];
					$expense_detail->save();				
				}
				DB::commit();
				return $this->setRpta();
			} 
			return $middleRpta;
		}
		catch ( Exception $e )
		{
			DB::rollback();
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
			$solicitud  = Solicitud::where('token', $inputs['token'] )->first();
			if( is_null( $solicitud ) )
				return $this->warninException( 'No se encontro la solicitud con token: '.$inputs['token'] , __FUNCTION__ , __LINE__ , __FILE__ );
			
			$oldIdEstado = $solicitud->id_estado;
			$solicitud->id_estado = REGISTRADO;
			$solicitud->save();

			$rpta = $this->setStatus( $oldIdEstado, REGISTRADO, Auth::user()->id, USER_CONTABILIDAD , $solicitud->id );
			if ( $rpta[status] == ok )
			{
				Session::put( 'state' , R_GASTO );
				DB::commit();
				return $rpta;
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

	public function viewExpense($token)
	{
		$solicitud = Solicitud::where('token',$token)->firstOrFail();
		if(count($solicitud)>0)
		{
			$expenses = Expense::where('idsolicitud',$solicitud->idsolicitud)->get();
		}
		$gasto_total = 0;
		foreach ($expenses as $expense)
		{
			$gasto_total += $expense->monto;
		}

		$data = [
			'solicitude' => $solicitud,
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



	public function reportExpense($token)
	{
		$solicitud = Solicitud::where('token',$token)->firstOrFail();
		$detalle = $solicitud->detalle;
		$jDetalle = json_decode( $solicitud->detalle->detalle );
		$clientes   = array();
		$cmps = array();
		foreach($solicitud->clients as $client)
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
		if($solicitud->createdBy->type == REP_MED )
			$created_by = $solicitud->rm->full_name;
		else if ($solicitud->createdBy->type == SUP )
			$created_by = $solicitud->sup->full_name;
		else
			$created_by = 'Usuario no Autorizado';
		$dni = new BagoUser;
		$dni = $dni->dni($solicitud->createdBy->username);
		if ($dni[status] == ok )
			$dni = $dni[data];
		else
			$dni = '';
		$expenses = $solicitud->expenses;
		$aproved_user = User::where( 'id' , $solicitud->acceptHist->updated_by )->firstOrFail();
		if( $aproved_user->type == GER_PROD )
		{
			$name_aproved = $aproved_user->gerprod->descripcion;
			$charge = "Gerente de Producto";
		}
		if( $aproved_user->type == SUP )
		{
			$name_aproved = $aproved_user->sup->nombres;
			$charge = "Supervisor";
		}
		if ( $solicitud->detalle->idmoneda == DOLARES )
		{
			foreach( $expenses as $expense )
			{
				$eTc = ChangeRate::where('fecha' , $expense->fecha_movimiento )->first();
				if ( is_null( $eTc ) )
					$expense->tc = ChangeRate::getTc();
				else	
					$expense->tc = $eTc;
				$expense->monto = round ( $expense->monto * $expense->tc->compra , 2 , PHP_ROUND_HALF_DOWN );
			}
		}

		$total = $expenses->sum('monto');
		$data = array(
			'solicitud'  => $solicitud,
			'detalle'	 => $jDetalle,
			'clientes'	 => $clientes,
			'cmps'		 => $cmps,
			'date'		 => array( 'toDay' => $solicitud->created_at , 'lastDay' => $jDetalle->fecha_entrega ),
			'name'       => $name_aproved,
			'dni' 		 => $dni,
			'created_by' => $created_by,
			'charge'     => $charge,
			'expenses'   => $expenses,
			'total'      => $total
		);
		$data['balance'] = $this->reportBalance( $solicitud , $detalle , $jDetalle , $total );
		$html = View::make('Expense.report',$data)->render();
		return PDF::load($html, 'A4', 'landscape')->show();
	}

	public function reportExpenseFondo($token)
	{
        $fondo = Solicitud::where('token',$token)->first();
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
			$solicitud = Solicitud::find( $inputs['idsolicitud']);
			if ( is_null( $solicitud ) )
				return $this->warningException( 'No se encontro la solicitud con id: '.$inputs['idsolicitud'] , __FUNCTION__ , __LINE__ , __FILE__ ); 
			$data = array( 'solicitud' => $solicitud , 'expense'   => $solicitud->expenses );
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