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
use \yajra\Pdo\Oci8\Exceptions\Oci8Exception;
use \PDF2;
use \Dmkt\InvestmentType;
use \Users\Supervisor;
use \Users\Visitador;
use \Fondo\FondoMkt;

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
        $rules = array( 'token'    		   => 'required|string|size:40|exists:'.TB_SOLICITUD.',token' ,
                        'proof_type'       => 'required|integer|min:1|exists:'.TB_TIPO_COMPROBANTE.',id' ,
                        'desc_expense'     => 'required|string|min:1',
                        'tipo_gasto'	   => 'required|array|min:1|each:integer|each:min,1|each:exists,'.TB_TIPO_GASTO.',id',
                        'quantity'		   => 'required|array|min:1|each:integer|each:min,1',
                        'description'	   => 'required|array|min:1|each:string|each:min,1',
                        'total_item'	   => 'required|array|min:1|each:numeric|each:min,1',
                        'total_expense'    => 'required|numeric|min:1' );  

        $validator = Validator::make( $inputs , $rules );
        if ( $validator->fails() ) 
            return $this->warningException( substr( $this->msgValidator( $validator ) , 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
        else
        {
        	$rules 		= array();
        	$proofType = ProofType::find( $inputs['proof_type'] );
        	$solicitud = Solicitud::where( 'token' , $inputs[ 'token' ] )->first();
        	$date 	   = $this->getExpenseDate( $solicitud , 1 );
        	$rules[ 'fecha_movimiento' ] = 'required|string|date_format:"d/m/Y"|after:' . $date[ 'startDate' ] . '|before:' . $date[ 'endDate'] ;
        	$validator = Validator::make( $inputs , $rules );
        	$validator->sometimes( 'number_prefix' , 'required|string|min:1|max:4' , function( $input ) use( $proofType )
            {
                return $proofType->marca != 'N';
            });
            $validator->sometimes( 'number_serie' , 'required|numeric|min:1|digits_between:1,12' , function( $input ) use( $proofType )
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
			DB::beginTransaction();
			$inputs = Input::all();
			$middleRpta = $this->validateInputExpense( $inputs );
			if ( $middleRpta[ status ] === ok )
			{
				if ( isset( $inputs[ 'idgasto' ] ) )
					$expense = Expense::find( $inputs[ 'idgasto' ] );
				else
				{
					$expense 	 = new Expense;
					$expense->id = $expense->lastId() + 1;
		        }
		        
			    $proof = ProofType::find( $inputs[ 'proof_type' ] );
	    		if( $proof->code != 'N' && ! isset( $inputs[ 'idgasto' ] ) && ! is_null( $inputs[ 'ruc' ] ) && ! is_null( $inputs[ 'number_prefix' ] ) && ! is_null( $inputs[ 'number_serie' ] ) )
		    	{
		    		$row_expense = Expense::where( 'ruc' , $inputs[ 'ruc' ] )->where( 'num_prefijo' ,$inputs[ 'number_prefix' ] )->where( 'num_serie' , $inputs[ 'number_serie' ] )->get();	
					if( $row_expense->count() > 0 )
						return $this->warningException( 'Ya existe un gasto registrado con Ruc: ' . $inputs[ 'ruc' ] . ' numero: '.$inputs[ 'number_prefix' ] . '-' . $inputs[ 'number_serie' ] , __FUNCTION__ , __LINE__ , __FILE__ );
				}

				if( $proof->igv == 1 )
				{
					$expense->igv      = $inputs['igv'];
					$expense->imp_serv = $inputs['imp_service'];
					$expense->sub_tot  = $inputs['sub_total_expense'];
				}	
	            else
	            {
	                $expense->igv      = null;
	                $expense->imp_serv = null;
	                $expense->sub_tot  = null;
	            }

		        $date 			  = $inputs['fecha_movimiento'];
		        list($d, $m, $y)  = explode('/', $date);
		        $d 				  = mktime(11, 14, 54, $m, $d, $y);
				$inputs[ 'date' ] = date("Y/m/d", $d );
				$solicitud 		  = Solicitud::where( 'token' , $inputs[ 'token' ] )->first();
				$inputs[ 'id_solicitud' ] = $solicitud->id;
				$this->setExpense( $expense , $inputs );

				//Detail Expense
				ExpenseItem::where( 'id_gasto' , $expense->id )->delete();
				if ( $proof->igv == 1 && array_sum( $inputs[ 'total_item' ] ) == ( $inputs[ 'total_expense' ] - $inputs[ 'imp_service' ] ) )
					$pIGV = 1 + ( Table::getIgv()->numero / 100 );
				else
					$pIGV = 1;
				for( $i = 0 ; $i < count( $inputs[ 'quantity' ] ) ; $i++ )
				{
					$expense_detail = new ExpenseItem;
					$expense_detail->id = $expense_detail->lastId() + 1 ;
					$expense_detail->id_gasto = $expense->id;
					$expense_detail->cantidad = $inputs['quantity'][$i];
					$expense_detail->descripcion = $inputs['description'][$i];
					$expense_detail->tipo_gasto = $inputs['tipo_gasto'][$i];
					$expense_detail->importe = round( $inputs['total_item'][$i] / $pIGV , 2 , PHP_ROUND_HALF_DOWN ) ;
					$expense_detail->save();				
				}
				DB::commit();
				return $this->setRpta();
			}
			DB::rollback();
			return $middleRpta;
		}
		catch( Oci8Exception $e )
		{
			DB::rollback();
			return $this->internalException( $e , __FUNCTION__ , DB );
		}
		catch( Exception $e )
		{
			DB::rollback();
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	private function setExpense( $expense , $inputs )
	{
		$expense->idcomprobante    = $inputs['proof_type'];
		$expense->num_prefijo      = $inputs['number_prefix'];
		$expense->num_serie 	   = $inputs['number_serie'];
		$expense->ruc 			   = $inputs['ruc'];
		$expense->razon 		   = $inputs['razon'];
		$expense->monto 		   = $inputs['total_expense'];
		$expense->descripcion 	   = $inputs['desc_expense'];
        $expense->fecha_movimiento = $inputs[ 'date' ];
        $expense->idcomprobante    = $inputs['proof_type'];
 		$expense->id_solicitud 	   = $inputs[ 'id_solicitud'];
 		if ( isset( $inputs[ 'rep' ] ) )
			$expense->reparo = $inputs['rep'];

 		if ( isset( $inputs[ 'idregimen' ] ) )
            if ( $inputs['idregimen'] == 0 )
            {
            	$expense->idtipotributo = 0;
            	$expense->monto_tributo = 0;
            }
            else
            {
        		$expense->idtipotributo = $inputs['idregimen'];
            	$expense->monto_tributo = $inputs['monto_regimen'];    	
            }
		$expense->save();
	}
	

	public function deleteExpense()
	{
		try
		{
			DB::beginTransaction();
			$inputs = Input::all();
			$expense = Expense::find( $inputs['gastoId'] );
			if ( is_null( $expense ) )
				return $this->warninException( 'No se encontro el registro del gasto con Id: '.$inputs['gastoId'] , __FUNCTION__ , __LINE__ , __FILE__ );
			ExpenseItem::where( 'id_gasto' , $inputs[ 'gastoId' ] )->delete();
			$expense->delete();
			DB::commit();
			return $this->setRpta();
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
			$inputs 	  = Input::all();
			$expense      = Expense::find( $inputs[ 'idgasto' ] );
			if ( is_null( $expense ) )
				return $this->warninException( 'No existe registro del gasto con Id: ' . $inputs[ 'idgasto' ] , __FUNCTION__ , __LINE__ , __FILE__ );
			return $this->setRpta( array( 'expenseItems' => $expense->items , 'expense' => $expense ) );
		}
		catch ( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	private function renewFondo( $solicitud , $monto_renovado )
	{
		$fondoMktController = new FondoMKt;
		$tc = ChangeRate::getTc();
		$detalle = $solicitud->detalle;
		if ( $detalle->id_moneda == DOLARES )
			$tasaCompra = $tc->compra;
		elseif ( $detalle->id_moneda == SOLES )
			$tasaCompra = 1;
		$fondoDataHistories = array();
		if ( $solicitud->idtiposolicitud == SOL_REP )
		{
			$solicitudProducts = $solicitud->products;
			$fondo_type 	   = $solicitud->products[ 0 ]->id_tipo_fondo_marketing;
			$monto_aprobado    = $solicitud->detalle->monto_aprobado;
 		    foreach( $solicitudProducts as $solicitudProduct )
		    {
		    	$fondo 			= $solicitudProduct->thisSubFondo;
		    	$oldSaldo       = $fondo->saldo;
		    	$oldSaldoNeto   = $fondo->saldo_neto;
		    	$monto_renovado_final = ( $monto_renovado / $monto_aprobado ) * $solicitudProduct->monto_asignado;
		    	$monto_renovado_final = $monto_renovado_final * $tasaCompra;
		    	
		    	$fondo->saldo 		+= round( $monto_renovado_final , 2 , PHP_ROUND_HALF_DOWN );
		    	$fondo->saldo_neto  += round( $monto_renovado_final , 2 , PHP_ROUND_HALF_DOWN );
		    	$fondo->save();
		    	$fondoDataHistories[] = array( 'idFondo' => $fondo->id , 'idFondoTipo' => $fondo_type ,
                                               'oldSaldo' => $oldSaldo , 'oldSaldoNeto' => $oldSaldoNeto , 
                                               'newSaldo' => $fondo->saldo , 'newSaldoNeto' => $fondo->saldo_neto , 'reason' => FONDO_DEVOLUCION );
		    }
		}
		elseif ( $solicitud->idtiposolicitud == SOL_INST )
		{
			$fondo = $solicitud->detalle->thisSubFondo;
			$oldSaldo = $fondo->saldo;
			$oldSaldoNeto = $fondo->saldo_neto;
			$fondo->saldo 		+= $monto_renovado;
			$fondo->saldo_neto  += $monto_renovado;
			$fondo->save();
			$fondoDataHistories[] = array( 'idFondo' => $fondo->id , 'idFondoTipo' => INVERSION_INSTITUCIONAL ,
                                           'oldSaldo' => $oldSaldo , 'oldSaldoNeto' => $oldSaldoNeto , 
                                           'newSaldo' => $fondo->saldo , 'newSaldoNeto' => $fondo->saldo_neto , 'reason' => FONDO_DEVOLUCION );
		}
		\Log::error( $fondoDataHistories );
		$fondoMktController->setFondoMktHistories( $fondoDataHistories , $solicitud->id );  
	}

	public function confirmDiscount()
	{
		try
		{
			DB::beginTransaction();
			$inputs     = Input::all();
			$middleRpta = $this->validateDiscount( $inputs );
			if ( $middleRpta[ status] == ok )
			{
				$solicitud  = Solicitud::where( 'token' , $inputs[ 'token' ] )->first();
				$detalle    = $solicitud->detalle;
				$totalGasto = $solicitud->expenses->sum( 'monto' );
				if ( $solicitud->id_estado != REGISTRADO )
					return $this->warningException( 'La solicitud no esta habilitado para que realize el descuento. Se requiere que se culmine con el Registro de Gastos' , __FUNCTION__ , __LINE__ , __FILE__ );
				if ( $totalGasto >= $detalle->monto_aprobado )
					return $this->warningException( 'No existe un saldo en contra del responsable para realizar un descuento' , __FUNCTION__ , __LINE__ , __FILE__ );
				$jDetalle   = json_decode( $detalle->detalle );
				if ( isset( $jDetalle->descuento ) )
					return $this->warningException( 'Ya ha registrado el Descuento' , __FUNCTION__ , __LINE__ , __FILE__ );
				
				$monto_descuento           = $detalle->monto_aprobado - $totalGasto;
				$jDetalle->descuento 	   = $inputs[ 'periodo' ];
				$jDetalle->monto_descuento = $monto_descuento;
				$detalle->detalle    	   = json_encode( $jDetalle );
				$detalle->save();
				$this->renewFondo( $solicitud , $monto_descuento );
				DB::commit();
				return $this->setRpta();
			}
			return $middleRpta;
		}
		catch( Exception $e )
		{
			DB::rollback();
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	private function setDevolucion( $solicitud , $detalle , $jDetalle , $inputs , $balance )
	{
		$solicitud->id_estado    = DEVOLUCION;
		$jDetalle->numero_operacion_devolucion = $inputs[ 'numero_operacion_devolucion' ];
		$jDetalle->monto_descuento             = $balance;
		$detalle->detalle = json_encode( $jDetalle );
		$detalle->save();
		return USER_TESORERIA;
	}

	private function setDataInstitucional( $solicitud , $inputs )
	{
		$solicitud->id_inversion = $inputs[ 'inversion' ];
		$solicitud->id_actividad = $inputs[ 'actividad' ];
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

			$oldIdEstado    = $solicitud->id_estado;
			$detalle 		= $solicitud->detalle;
			$monto_aprobado = $detalle->monto_aprobado;
			$totalGasto 	= $solicitud->expenses->sum( 'monto' );
			$balance    	= $monto_aprobado - $totalGasto;

			$tc = ChangeRate::getTc();
			if ( $solicitud->detalle->id_moneda == SOLES )
				$tasa = 1;
			elseif ( $solicitud->detalle->id_moneda == DOLARES )
				$tasa = $tc->compra;

			$balance = $balance * $tasa;

			$jDetalle       = json_decode( $detalle->detalle );

			if ( $solicitud->idtiposolicitud == SOL_REP )
				if ( $balance > MONTO_DESCUENTO_PLANILLA )
					if ( isset( $inputs[ 'numero_operacion_devolucion' ] ) && ! empty( trim( $inputs[ 'numero_operacion_devolucion'] ) ) )
					{
						$userTo = $this->setDevolucion( $solicitud , $detalle , $jDetalle , $inputs , $balance );
					}
					else
						return array( 
							status  => 'Info' , 
				        	'View'  => View::make( 'Dmkt.Register.expense-missing-data' , array( 'devolucion' => true ) )->render() ,
					        'Type'  => 'D' ,
					        'Title' => 'Registro de la Operacion de Devolución' );
				elseif ( $balance <= MONTO_DESCUENTO_PLANILLA && $balance >= 0  )
				{
					$solicitud->id_estado = REGISTRADO;
					$userTo 			  = USER_CONTABILIDAD;
				}
				else
					return $this->warningException( 'No se puede registrar los gastos si exceden al monto depositado' , __FUNCTION__ , __FILE__ , __LINE__ );
			elseif( $solicitud->idtiposolicitud == SOL_INST )
				if( $balance > MONTO_DESCUENTO_PLANILLA )
				{
					if ( isset( $inputs[ 'inversion'] ) && isset( $inputs[ 'actividad' ] ) && isset( $inputs[ 'numero_operacion_devolucion' ] ) )
					{
						$this->setDataInstitucional( $solicitud , $inputs );	
						$userTo = $this->setDevolucion( $solicitud , $detalle , $jDetalle , $inputs ,$balance );
					}
					else
					{
						$investments = InvestmentType::orderInst();
						return array( 
							status => 'Info' , 
							'View' => View::make( 'Dmkt.Register.expense-missing-data' , array( 'investments' => $investments , 'activities' => Activity::order() , 'devolucion' => true ) )->render() ,
							'Type' => 'ID' ,
							'Title' => 'Registro de la Operacion de Devolución , Inversion y Actividad' );
					}
				}
				elseif ( $balance <= MONTO_DESCUENTO_PLANILLA && $balance >= 0 )
					if ( isset( $inputs[ 'inversion'] ) && isset( $inputs[ 'actividad' ] ) )
					{
						$this->setDataInstitucional( $solicitud , $inputs );
						$solicitud->id_estado = REGISTRADO;
						$userTo = USER_CONTABILIDAD;
					}
					else
					{
						$investments = InvestmentType::orderInst();
						return array( 
							status => 'Info' , 
							'View' => View::make( 'Dmkt.Register.expense-missing-data' , array( 'investments' => $investments , 'activities' => Activity::order() ) )->render() ,
							'Type' => 'I' ,
							'Title' => 'Registro de la Inversion y Actividad');
					}
				else
					return $this->warningException( 'No se puede registrar los gastos si exceden al monto depositado' , __FUNCTION__ , __FILE__ , __LINE__ );
			else
				return $this->warningException( 'No se pudo identificar el tipo de solicitud' , __FUNCTION__ , __LINE__ , __FILE__ );
	
			$solicitud->save();

			$rpta = $this->setStatus( $oldIdEstado, $solicitud->id_estado , Auth::user()->id, $userTo , $solicitud->id );
			if ( $rpta[status] == ok )
			{
				Session::put( 'state' , R_GASTO );
				DB::commit();
			}
			else
				DB::rollback();
			return $rpta;
		}
		catch (Exception $e)
		{
			DB::rollback();
			return $this->internalException( $e , __FUNCTION__ );
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

	public function getSpecialty($cmp = null){
		$result = null;
		if(!is_null($cmp)){
			$query = "SELECT " .
							"P.PEFCODPERS as CMP, " .
							"P.PEFPATERNO as APELLIDO, " .
							"E1.NOMESP AS ESPECIALIDAD1, " .
							"E2.NOMESP AS ESPECIALIDAD2 " .
						"FROM " .
								TB_DOCTOR." P " .
								"LEFT JOIN " .
								  "FICPE.ESPECIAL E1 " .
								  "ON TO_NUMBER(NVL(TRIM(P.PEFESPECIAL1),'0')) = E1.CODESP " .
								"LEFT JOIN " .
								  "FICPE.ESPECIAL E2 " .
								  "ON TO_NUMBER(NVL(TRIM(P.PEFESPECIAL2),'0')) = E2.CODESP " .
						"WHERE " .
						  "P.PEFNRODOC1 = ".$cmp;
			Log::error("=========================================================000");
			Log::error($query);					  
			Log::error("=========================================================000");
			$result = DB::select($query)[0];
		}
		return $result;
	}

	public function getZonaRep($id){
		$result = null;
		$rep = Visitador::where('VISVISITADOR', '=', $id)->first();
		if(isset($rep->visnivel4geog))
			$result = DB::select("SELECT * FROM FICPE.NIVEL4GEOG where N4GNIVEL4GEOG=".$rep->visnivel4geog)[0]->n4gdescripcion;
		return $result;
	}
	public function getZonaSup($id){
		$result = null;
		$sup = Supervisor::where('SUPSUPERVISOR', '=', $id)->first();
		if(isset($rep->supnivel4geog))
			$result = DB::select("SELECT * FROM FICPE.NIVEL4GEOG where N4GNIVEL4GEOG=".$sup->supnivel4geog)[0]->n4gdescripcion;
		return $result;
	}

	public function reportExpense($token)
	{
		$solicitud = Solicitud::where('token',$token)->firstOrFail();
		$detalle   = $solicitud->detalle;
		$jDetalle  = json_decode( $solicitud->detalle->detalle );
		$clientes  = array();
		$cmps      = array();
		$getSpecialty = array();
		foreach($solicitud->clients as $client)
        {
			$clientes[]     = $client->clientType->descripcion . ': ' . $client->{$client->clientType->relacion}->full_name;
			if(!is_null($client->{$client->clientType->relacion}->pefnrodoc1))
				$cmps[]         = $client->{$client->clientType->relacion}->pefnrodoc1;
			$getSpecialtyResult = $this->getSpecialty($client->{$client->clientType->relacion}->pefnrodoc1);
			if(!is_null($getSpecialtyResult)){
				$getSpecialty[] = $getSpecialtyResult->especialidad1;
			}
        }
		$getSpecialty = implode("<br><br>", $getSpecialty);
		$clientes     = implode('<br><br>',$clientes);
		$cmps         = implode('<br><br> ',$cmps);
		$zona = null;
		
		$created_by = $solicitud->asignedTo->personal->getFullName();
		$dni = new BagoUser;
		$dni = $dni->dni($solicitud->asignedTo->username);
		if ($dni[status] == ok )
			$dni = $dni[data];
		else
			$dni = '';
		$expenses = $solicitud->expenses;
		$aproved_user = User::where( 'id' , $solicitud->approvedHistory->updated_by )->firstOrFail();
	
		$name_aproved = $aproved_user->personal->getFullName();
		$charge = $aproved_user->userType->descripcion;

		if ( $solicitud->detalle->idmoneda == DOLARES )
		{
			foreach( $expenses as $expense )
			{
				$eTc = ChangeRate::where( 'fecha' , $expense->fecha_movimiento )->first();
				if ( is_null( $eTc ) )
					$expense->tc = ChangeRate::getTc();
				else	
					$expense->tc = $eTc;
				$expense->monto = round ( $expense->monto * $expense->tc->compra , 2 , PHP_ROUND_HALF_DOWN );
			}
		}

		$total = $expenses->sum('monto');
		$data  = array( 'solicitud'    => $solicitud,
						'detalle'      => $jDetalle,
						'clientes'     => $clientes,
						'cmps'         => $cmps,
						'getSpecialty' => $getSpecialty,
						'date'         => array( 'toDay' => $solicitud->created_at , 'lastDay' => $jDetalle->fecha_entrega ),
						'name'         => $name_aproved,
						'dni'          => $dni,
						'created_by'   => $created_by,
						'charge'       => $charge,
						'expenses'     => $expenses,
						'zona'			=> $zona,
						'total'        => $total );
		$data['balance'] = $this->reportBalance( $solicitud , $detalle , $jDetalle , $total );
		$html = View::make( 'Expense.report' , $data )->render();
		// return View::make( 'Expense.report' , $data )->render();
		//return View::make('Expense.report',$data);//->render();
		//return $html;
		//return PDF::load( $html , 'A4' , 'landscape' )->show();
		return PDF2::loadHTML( $html )->setPaper( 'a4' , 'landscape' )->stream();
		// return $html;
	}

	public function reportExpenseFondo($token)
	{
		$fondo    = Solicitud::where('token',$token)->first();
		$detalle  = $fondo->detalle;
		$jDetalle = json_decode( $detalle->detalle );
		$expense  = $fondo->expenses;
		$dni      = new BagoUser;
		$dni      = $dni->dni($fondo->createdBy->username);
		if ($dni[status] == ok )
			$dni = $dni[data];
		else
			$dni = '';
        $data = array(  'fondo'    => $fondo,
			            'detalle'  => $jDetalle,
			            'dni'	   => $dni,
			            'date'     => $this->getDay(),
			            'expense'  => $expense );
        $data['balance'] = $this->reportBalance( $fondo , $detalle , $jDetalle , $expense->sum('monto') );
        
        $html = View::make('Expense.report-fondo',$data)->render();
        //return $html;
        //return PDF2::loadHTML( $html )->setPaper( 'a4' , 'landscape' )->stream();
        return PDF2::loadHTML( $html , 'a4' , 'portrait' )->stream();
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
			$data = array( 'solicitud' => $solicitud , 'expenses'   => $solicitud->expenses );
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

	private function validateDiscount( $inputs )
    {
        $rules = array( 'token'   => 'required|string|size:40|exists:solicitud,token' ,
                        'periodo' => 'required|string|size:7|date_format:"m-Y"|after:'  . date( '01-m-Y' ) );  
        $validator = Validator::make( $inputs, $rules );
        if ( $validator->fails() ) 
            return $this->warningException( substr( $this->msgValidator( $validator ) , 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
	    return $this->setRpta(); 
    }
}