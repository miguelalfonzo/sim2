<?php

namespace Alert;

use \BaseController;
use \Exception;
use \Carbon\Carbon;
use \Dmkt\Solicitud;
use \Dmkt\SolicitudClient;
use \Auth;
use \Illuminate\Database\Eloquent\Collection;
use \Parameter\Parameter;
use \View;
use \DB;

class AlertController extends BaseController
{
	public function show()
	{
		$data = array('alerts' => $this->alertConsole2());
		return View::make('template.User.alerts', $data);
	}

	public function showAlerts()
	{
		return array('status' => 'OK', 'alerts' => $this->alertConsole2());
	}

    public function alertConsole2()
    {
    	$result = array();
    	$result['alert'] = array();
    	
    	$clientAlert = $this->clientAlert2();
    	if($clientAlert[ 'msg' ] != "" )
    		$result['alert'][] = $clientAlert;
    	
    	$expenseAlert = $this->expenseAlert2();
    	if($expenseAlert[ 'msg' ] != "")
    		$result['alert'][] = $expenseAlert;
    	
    	$timeAlert = $this->compareTime2();
    	if($timeAlert[ 'msg' ] != "")
    		$result['alert'][] = $timeAlert;
    	
    	return $result['alert'];
    }

    public function clientalert2()
    {
    	$user = Auth::user();
    	if( in_array( $user->type , [ REP_MED , SUP , GER_PROD , GER_PROM , GER_COM , CONT ] ) )
    	{
	    	$add;
	    	$msg;
	    	$result = [];				
	    	$clienteArray;
	    	$solicitudArray;
	    	$data = DB::table( 'VALIDACION_CLIENTE' )->get();
	    	$tiempo = Parameter::find( ALERTA_INSTITUCION_CLIENTE );
	    	foreach( $data as $register )
	    	{
	    		$msg = 'Las solicitudes ' . $register->ids_solicitud  . ' ' . $tiempo->mensaje ;
	 			$add = false;
	    		$ids_solicitud = explode( ',' , $register->ids_solicitud );
	    		foreach( $ids_solicitud as $id_solicitud )
	    		{
	    			$solicitud = Solicitud::find( $id_solicitud );
	    			if( $user->type === REP_MED )
	    			{
	    				if( $solicitud->id_user_assign == $user->id )
	    				{
	    					$add = true;
	    				}
	    			}
	    			elseif( in_array( $user->type , [ SUP , GER_PROD , GER_PROM ] , 1 ) )
	    			{
	    				$ids = $solicitud->gerente->lists( 'id_gerprod' );
	    				if( in_array( $user->id , $ids ) )
	    				{
	    					$add = true;
	    				}
	    			}
	    			elseif( in_array( $user->type , [ GER_COM , CONT ] , 1 ) )
	    			{
	    				$add = true;
	    			}
	    		}
	    		if( $add )
	    		{
	    			$clients = explode( ',' , $register->cliente );
	    			foreach( $clients as $client )
	    			{
	    				$client_data      = explode( '|' , $client );
	    				$solicitudCliente = SolicitudClient::where( 'id_cliente' , $client_data[ 0 ] )->where( 'id_tipo_cliente' , $client_data[ 1 ] )->first();
	    				$clientName     = $solicitudCliente->{$solicitudCliente->clientType->relacion}->full_name;
	    				$clienteArray[] = $clientName;
	    				$cliente = '( ' . $clientName . ' , ';
					} 
					$cliente = rtrim( $cliente , ', ' );
					$cliente .= ' ).';
					$msg .= ' ' . $cliente;
	    			$solicitudArray = $ids_solicitud;
	    			$result[]= 
	    			[
						'cliente'    => $clienteArray,
						'solicitude' => $ids_solicitud,
						'msg'        => $tiempo->mensaje
					];
					$clienteArray = [];
				}
	    	}
	    }
	    return array( 'type' => 'warning' , 'msg' => $msg , 'data' => $result, 'typeData' => 'clientAlert');
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


	public function timeAlert( $record , $method , $date )
	{
		$now = Carbon::now();
		$updated = new Carbon( $record->$date );
		return $updated->$method( $now );
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