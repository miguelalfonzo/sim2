<?php

namespace Alert;

use \BaseController;
use \Exception;
use \Carbon\Carbon;
use \Dmkt\Solicitud;
use \Auth;
use \Illuminate\Database\Eloquent\Collection;
use \Parameter\Parameter;
use \View;

class AlertController extends BaseController
{
	public function show()
	{
		$data = array('alerts' => $this->alertConsole2());
		return View::make('template.User.alerts', $data);
	}

	public function showAlerts(){
		return array('status' => 'OK', 'alerts' => $this->alertConsole2());
	}

	private function intersectRecords( $rs1 , $rs2 )
    {
    	$intersect = new Collection;
    	foreach( $rs1 as $r1 )
    	{
    		foreach( $rs2 as $r2 )
    		{
    			if ( $r2->id_cliente == $r1->id_cliente && $r2->id_tipo_cliente == $r1->id_tipo_cliente )
    			{
    				$intersect->add( $r2 );
    				break;
    			}
    		}
    	}
    	return $intersect;
    }

    public function alertConsole2()
    {
    	$result = array();
    	$result['alert'] = array();
    	$clientAlert = $this->clientAlert2();
    	$expenseAlert = $this->expenseAlert2();
    	$timeAlert = $this->compareTime2();
    	if($clientAlert[ 'msg' ] != "" )
    		$result['alert'][] = $clientAlert;
    	if($expenseAlert[ 'msg' ] != "")
    		$result['alert'][] = $expenseAlert;
    	if($timeAlert[ 'msg' ] != "")
    		$result['alert'][] = $timeAlert;
    	return $result['alert'];
    }
    public function alertConsole()
    {
    	$result = array();
    	$result['alert'] = array();
    	$clientAlert = $this->clientAlert();
    	$expenseAlert = $this->expenseAlert();
    	if($clientAlert[ 'msg' ] != "" )
    		$result['alert'][] = $clientAlert;
    	if($expenseAlert[ 'msg' ] != "")
    		$result['alert'][] = $expenseAlert;
    	return $result['alert'];
    }

    public function clientalert2()
    {
    	\Log::info( 'inicio de alerta2');
    	$msg = '';
    	$tipo_cliente_requerido = array( MEDICO , INSTITUCION );
    	$tiempo = Parameter::find( ALERTA_INSTITUCION_CLIENTE );
		$solicituds = Solicitud::all();
		foreach ( $solicituds as $key => $solicitud )
		{
			$clients = $solicitud->clients;
			$solicitud_tipo_cliente = array_unique( $clients->lists( 'id_tipo_cliente') );
			if ( count( array_intersect( $solicitud_tipo_cliente, $tipo_cliente_requerido ) ) <= 1 )
				unset( $solicituds[ $key ] );
		}
		$clientList         = array();
		$compare_second_id  = array();
		$compare_initial_id = array();
		$clienteArray       = array();
		$result             = array();
		foreach( $solicituds as $solicitud_inicial )
		{
			foreach ( $solicituds as $solicitud_secundaria )
			{
				if ( $solicitud_secundaria->id != $solicitud_inicial->id && ( ! in_array( $solicitud_secundaria->id , $compare_initial_id ) ) )
				foreach( $solicituds as $solicitud_final )
				{
					if( $solicitud_final->id != $solicitud_secundaria->id && 
						$solicitud_final->id != $solicitud_inicial->id && 
						( ! in_array( $solicitud_final->id , $compare_second_id ) ) )
					{
						$clients_inicial = $solicitud_inicial->clients()->select( 'id_cliente' , 'id_tipo_cliente' )->get();
						$clients_secundaria = $solicitud_secundaria->clients()->select( 'id_cliente' , 'id_tipo_cliente' )->get();
						$clients_final = $solicitud_final->clients()->select( 'id_cliente' , 'id_tipo_cliente' )->get();
						//echo $solicitud_inicial->id . '|' . $solicitud_secundaria->id . '|' . $solicitud_final->id . '<br>';
						$intersect_client = $this->intersectRecords( $clients_inicial , $clients_secundaria );
						$intersect_client = $this->intersectRecords( $intersect_client , $clients_final );
						$solicitud_tipo_cliente = array_unique( $intersect_client->lists( 'id_tipo_cliente' ) );
						if ( count( array_intersect( $solicitud_tipo_cliente, $tipo_cliente_requerido ) ) >= 2 )
						{
							$cliente = '( ';
							foreach ( $intersect_client as $client_inicial )
							{
								$clienteArray[] = $client_inicial->{$client_inicial->clientType->relacion}->full_name;
								$cliente .= $client_inicial->{$client_inicial->clientType->relacion}->full_name .  ' , ' ;
							} 
							$cliente = rtrim( $cliente , ', ' );
							$cliente .= ' ).';
							$msg .= 'Las solicitudes ' . $solicitud_inicial->id . ' , ' . $solicitud_secundaria->id . ' , ' . $solicitud_final->id . ' ' . $tiempo->mensaje . ' ' . $cliente;
							$result[]=array(
								'cliente'    => $clienteArray,
								'solicitude' => array($solicitud_inicial->id, $solicitud_secundaria->id, $solicitud_final->id),
								'msg'     => $tiempo->mensaje
							);
							$clienteArray = array();
						}
					}
				}
				$compare_second_id[] = $solicitud_secundaria->id;
			}
			$compare_second_id = array();
			$compare_initial_id[] = $solicitud_inicial->id;
		}
		\Log::info( 'fin de alerta2');
    	
		return array( 'type' => 'warning' , 'msg' => $msg, 'data' => $result, 'typeData' => 'clientAlert');
	}

    public function clientalert222()
    {
    	$result = array();
    	$msg = '';
    	$tipo_cliente_requerido = array( MEDICO , INSTITUCION );
    	$tiempo = Parameter::find( ALERTA_INSTITUCION_CLIENTE );
		$solicituds = Solicitud::all();
		foreach ( $solicituds as $key => $solicitud )
		{
			$clients = $solicitud->clients;
			$solicitud_tipo_cliente = array_unique( $clients->lists( 'id_tipo_cliente') );
			if ( count( array_intersect( $solicitud_tipo_cliente, $tipo_cliente_requerido ) ) <= 1 )
				unset( $solicituds[ $key ] );
		}
		$clientList = array();
		$solicituds_compare_id = array();
		foreach( $solicituds as $solicitud_inicial )
		{
			$clients_inicial = $solicitud_inicial->clients()->select( 'id_cliente' , 'id_tipo_cliente' )->get();
			foreach ( $solicituds as $solicitud_secundaria )
			{
				if ( $solicitud_inicial->id != $solicitud_secundaria->id && ! in_array( $solicitud_secundaria->id , $solicituds_compare_id ) )
				{
					$clients_secundaria = $solicitud_secundaria->clients()->select( 'id_cliente' , 'id_tipo_cliente' )->get();
					$cliente_inicial    = $this->intersectRecords( $clients_inicial , $clients_secundaria );
					if ( ! $cliente_inicial->isEmpty() )
					{
						foreach( $solicituds as $solicitud_final )
						{
							if ( $solicitud_inicial->id != $solicitud_final->id && $solicitud_final->id != $solicitud_secundaria->id && ! in_array( $solicitud_final->id , $solicituds_compare_id ) && ( $this->diffCreatedAt( $solicitud_inicial , $solicitud_secundaria , $solicitud_final ) <= $tiempo->valor ) )
							{
								$clients_final = $solicitud_final->clients()->select( 'id_cliente' , 'id_tipo_cliente' )->get();
								$cliente_inicial = $this->intersectRecords( $clients_inicial , $clients_final );
								if ( ! $cliente_inicial->isEmpty() )
								{
									$solicitud_tipo_cliente = array_unique( $cliente_inicial->lists( 'id_tipo_cliente' ) );
									if ( count( array_intersect( $solicitud_tipo_cliente, $tipo_cliente_requerido ) ) >= 2 )
									{
										$clienteArray=array();
										$cliente = '( ';
										foreach ( $cliente_inicial as $client_inicial ){
											$clienteArray[] = $client_inicial->{$client_inicial->clientType->relacion}->full_name;
											$cliente .= $client_inicial->{$client_inicial->clientType->relacion}->full_name .  ' , ' ;

										} 
										$cliente = rtrim( $cliente , ', ' );
										$cliente .= ' ).';
										$msg .= 'Las solicitudes ' . $solicitud_inicial->id . ' , ' . $solicitud_secundaria->id . ' , ' . $solicitud_final->id . ' ' . $tiempo->mensaje . ' ' . $cliente;
										$solicituds_compare_id[] = $solicitud_inicial->id;
										$solicituds_compare_id[] = $solicitud_secundaria->id;
										$result[]=array(
												'cliente'    => $clienteArray,
												'solicitude' => array($solicitud_inicial->id, $solicitud_secundaria->id, $solicitud_final->id),
												'msg'     => $tiempo->mensaje
											);
									}
								}
							}
						}
					}
				}
			}
		}
		return array( 'type' => 'warning' , 'msg' => $msg, 'data' => $result, 'typeData' => 'clientAlert');
    }

    public function clientalert()
    {
    	$msg = '';
    	$tipo_cliente_requerido = array( MEDICO , INSTITUCION );
    	$tiempo = Parameter::find( ALERTA_INSTITUCION_CLIENTE );
		$solicituds = Solicitud::all();
		foreach ( $solicituds as $key => $solicitud )
		{
			$clients = $solicitud->clients;
			$solicitud_tipo_cliente = array_unique( $clients->lists( 'id_tipo_cliente') );
			if ( count( array_intersect( $solicitud_tipo_cliente, $tipo_cliente_requerido ) ) <= 1 )
				unset( $solicituds[ $key ] );
		}
		$clientList = array();
		$solicituds_compare_id = array();
		foreach( $solicituds as $solicitud_inicial )
		{
			$clients_inicial = $solicitud_inicial->clients()->select( 'id_cliente' , 'id_tipo_cliente' )->get();
			foreach ( $solicituds as $solicitud_secundaria )
			{
				if ( $solicitud_inicial->id != $solicitud_secundaria->id && ! in_array( $solicitud_secundaria->id , $solicituds_compare_id ) )
				{
					$clients_secundaria = $solicitud_secundaria->clients()->select( 'id_cliente' , 'id_tipo_cliente' )->get();
					$cliente_inicial = $this->intersectRecords( $clients_inicial , $clients_secundaria );
					if ( ! $cliente_inicial->isEmpty() )
					{
						foreach( $solicituds as $solicitud_final )
						{
							if ( $solicitud_inicial->id != $solicitud_final->id && $solicitud_final->id != $solicitud_secundaria->id && ! in_array( $solicitud_final->id , $solicituds_compare_id ) && ( $this->diffCreatedAt( $solicitud_inicial , $solicitud_secundaria , $solicitud_final ) <= $tiempo->valor ) )
							{
								$clients_final = $solicitud_final->clients()->select( 'id_cliente' , 'id_tipo_cliente' )->get();
								$cliente_inicial = $this->intersectRecords( $clients_inicial , $clients_final );
								if ( ! $cliente_inicial->isEmpty() )
								{
									$solicitud_tipo_cliente = array_unique( $cliente_inicial->lists( 'id_tipo_cliente' ) );
									if ( count( array_intersect( $solicitud_tipo_cliente, $tipo_cliente_requerido ) ) >= 2 )
									{
										$cliente = '( ';
										foreach ( $cliente_inicial as $client_inicial )
											$cliente .= $client_inicial->{$client_inicial->clientType->relacion}->full_name .  ' , ' ; 
										$cliente = rtrim( $cliente , ', ' );
										$cliente .= ' ).';
										$msg .= 'Las solicitudes ' . $solicitud_inicial->id . ' , ' . $solicitud_secundaria->id . ' , ' . $solicitud_final->id . ' ' . $tiempo->mensaje . ' ' . $cliente;
										$solicituds_compare_id[] = $solicitud_inicial->id;
										$solicituds_compare_id[] = $solicitud_secundaria->id;
									}
								}
							}
						}
					}
				}
			}
		}
		return array( 'type' => 'warning' , 'msg' => $msg );
    }

    private function solMsg( $record1 , $record2 , $record3 )
    {
    	return $record1->id . /*' ' . $record1->created_at .*/ ' , ' . $record2->id . ' ' . /*$record2->created_at . ' ' .*/ $record3->id . ' ' /*. $record3->created_at*/ ; 
    }

    public function expenseAlert2()
	{
		$result = array();
		$solicituds = Solicitud::where( 'id_user_assign' , Auth::user()->id )->where( 'id_estado' , GASTO_HABILITADO )->get();
		$msg = '';
		$tiempo = Parameter::find( ALERTA_TIEMPO_ESPERA_POR_DOCUMENTO );
		foreach ( $solicituds as $solicitud )
		{
			$expenseHistory = $solicitud->expenseHistory;
			$lastExpense = $solicitud->lastExpense;
			if ( is_null( $lastExpense ) && ! is_null( $expenseHistory ) && $this->timeAlert( $expenseHistory , 'diffInDays' , 'updated_at' ) >= $tiempo->valor ){
				$msg .= 'La solicitud N° ' .  $solicitud->id . $tiempo->mensaje;
				$result[] = array(
					"solicitude" => $solicitud->id,
					"msg" => $tiempo->mensaje,
					);
			}
			else if ( ( ! is_null( $lastExpense ) ) && $this->timeAlert( $lastExpense , 'diffInDays' , 'updated_at' ) >= $tiempo->valor ){
				$msg .= 'La solicitud N° ' .  $solicitud->id . ' ' .  $tiempo->mensaje;	
				$result[] = array(
					"solicitude" => $solicitud->id,
					"msg" => $tiempo->mensaje,
					);
			}
		}
		return array( 'type' => 'warning' , 'msg' => $msg, 'typeData' => 'expenseAlert', 'data' => $result );
	}

	public function expenseAlert()
	{
		$solicituds = Solicitud::where( 'id_user_assign' , Auth::user()->id )->where( 'id_estado' , GASTO_HABILITADO )->get();
		$msg = '';
		$tiempo = Parameter::find( ALERTA_TIEMPO_ESPERA_POR_DOCUMENTO );
		foreach ( $solicituds as $solicitud )
		{
			$expenseHistory = $solicitud->expenseHistory;
			$lastExpense = $solicitud->lastExpense;
			if ( is_null( $lastExpense ) && ! is_null( $expenseHistory ) && $this->timeAlert( $expenseHistory , 'diffInDays' , 'updated_at' ) >= $tiempo->valor )
				$msg .= 'La solicitud N° ' .  $solicitud->id . $tiempo->mensaje;
			else if ( ( ! is_null( $lastExpense ) ) && $this->timeAlert( $lastExpense , 'diffInDays' , 'updated_at' ) >= $tiempo->valor )
				$msg .= 'La solicitud N° ' .  $solicitud->id . ' ' .  $tiempo->mensaje;	
		}
		return array( 'type' => 'warning' , 'msg' => $msg );
	}

	public function timeAlert( $record , $method , $date )
	{
		$now = Carbon::now();
		$updated = new Carbon( $record->$date );
		return $updated->$method( $now );
	}

	private function diffCreatedAt( $record1 , $record2 , $record3 )
	{
		$date1 = new Carbon( $record1->created_at );
		$date2 = new Carbon( $record2->created_at );
		$date3 = new Carbon( $record3->created_at );
		$min = $date1->min( $date2 , $date3 );
		$max = $date1->max( $date2 , $date3 );
		$rpta = $max->diffInDays( $min );
		return $rpta;
	}

	public function compareTime( $record , $method )
	{
		$tiempo = Parameter::find( ALERTA_TIEMPO_REGISTRO_GASTO );
		if ( $this->timeAlert( $record , 'diffInDays' , 'created_at' ) >= $tiempo->valor )
			return array( 'type' => 'warning' , 'msg' => $tiempo->mensaje );
		else
			return array();
	}

	public function compareTime2()
	{
		$solicituds = Solicitud::where( 'id_user_assign' , Auth::user()->id )->where( 'id_estado' , GASTO_HABILITADO )->get();
		$msg = '';
		$result = array();
		$tiempo = Parameter::find( ALERTA_TIEMPO_REGISTRO_GASTO );
		foreach ( $solicituds as $solicitud )
		{
			if ( $this->timeAlert( $solicitud , 'diffInDays' , 'created_at' ) >= $tiempo->valor )
			{
				$msg .= 'La solicitud N° ' .  $solicitud->id . $tiempo->mensaje;
				$result[] = array(
					"solicitude" => $solicitud->id,
					"msg" => $tiempo->mensaje,
					);
			}
		}
		return array( 'type' => 'warning' , 'msg' => $msg, 'typeData' => 'expenseAlert' , 'data' => $result );
	}
}