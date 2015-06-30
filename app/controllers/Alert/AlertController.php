<?php

namespace Alert;

use \BaseController;
use \Exception;
use \Carbon\Carbon;
use \Dmkt\Solicitud;
use \Auth;
use \Illuminate\Database\Eloquent\Collection;
use \Parameter\Parameter;

class AlertController extends BaseController
{

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

    public function alertConsole()
    {
    	$clientAlert = $this->clientAlert();
    	$expenseAlert = $this->expenseAlert();
    	return array( 'type' => 'warning' , 'msg' => $clientAlert[ 'msg' ] . $expenseAlert[ 'msg' ] );
    }

    public function clientalert()
    {
    	$msg = '';
    	$tipo_cliente_requerido = array( MEDICO , INSTITUCION );
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
					foreach( $solicituds as $solicitud_final )
					{
						if ( $solicitud_inicial->id != $solicitud_final->id && $solicitud_final->id != $solicitud_secundaria->id && ! in_array( $solicitud_final->id , $solicituds_compare_id ) )
						{
							$clients_final = $solicitud_final->clients()->select( 'id_cliente' , 'id_tipo_cliente' )->get();
							$cliente_inicial = $this->intersectRecords( $clients_inicial , $clients_final );
							$solicitud_tipo_cliente = array_unique( $clients_inicial->lists( 'id_tipo_cliente' ) );
							if ( count( array_intersect( $solicitud_tipo_cliente, $tipo_cliente_requerido ) ) >= 2 )
							{
								$cliente = '<ul class="list-group">';
								foreach ( $cliente_inicial as $client_inicial )
									$cliente .= '<li class="list-group-item">' . $client_inicial->{$client_inicial->clientType->relacion}->full_name .  '.</li>' ; 
								$cliente .= '</ul>';
								$msg .= '<h5>Las solicitudes ' . $solicitud_inicial->id . ' , ' . $solicitud_secundaria->id . ' , ' . $solicitud_final->id . ' ' . MSG_CLIENT_ALERT . ' ' . $cliente . '</h5>';
								$solicituds_compare_id[] = $solicitud_inicial->id;
								$solicituds_compare_id[] = $solicitud_secundaria->id;
							}
						}
					}
				}
			}
		}
		return array( 'type' => 'warning' , 'msg' => $msg );
    }

	public function expenseAlert()
	{
		$solicituds = Solicitud::where( 'id_user_assign' , Auth::user()->id )->where( 'id_estado' , GASTO_HABILITADO )->get();
		$msg = '';
		$tiempo = Parameter::find( ALERTA_TIEMPO_ESPERA_POR_DOCUMENTO )->valor;
		foreach ( $solicituds as $solicitud )
		{
			$expenseHistory = $solicitud->expenseHistory;
			$lastExpense = $solicitud->lastExpense;
			if ( is_null( $lastExpense ) && ! is_null( $expenseHistory ) && $this->timeAlert( $expenseHistory , 'diffInDays' , 'updated_at' ) >= $tiempo )
				$msg .= '<h5>La solicitud N° ' .  $solicitud->id . MSG_EXPENSE_ALERT . '</h5>';
			else if ( ( ! is_null( $lastExpense ) ) && $this->timeAlert( $lastExpense , 'diffInDays' , 'updated_at' ) >= $tiempo )
				$msg .= '<h5>La solicitud N° ' .  $solicitud->id . ' ' .  MSG_EXPENSE_ALERT . '</h5>';	
		}
		return array( 'type' => 'warning' , 'msg' => $msg );
	}

	public function timeAlert( $record , $method , $date )
	{
		$now = Carbon::now();
		$updated = new Carbon( $record->$date );
		return $updated->$method( $now );
	}

	public function compareTime( $record , $method )
	{
		$tiempo = Parameter::find( ALERTA_TIEMPO_REGISTRO_GASTO )->valor;
		if ( $this->timeAlert( $record , 'diffInDays' , 'created_at' ) >= $tiempo->valor )
			return array( 'type' => 'warning' , 'msg' => MSG_MONTH_ALERT );
		else
			return array();
	}

}