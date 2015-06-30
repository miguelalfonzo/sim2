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
					foreach( $solicituds as $solicitud_final )
					{
						if ( $solicitud_inicial->id != $solicitud_final->id && $solicitud_final->id != $solicitud_secundaria->id && ! in_array( $solicitud_final->id , $solicituds_compare_id ) && ( $this->diffCreatedAt( $solicitud_inicial , $solicitud_secundaria , $solicitud_final ) <= $tiempo->valor ) )
						{
							$clients_final = $solicitud_final->clients()->select( 'id_cliente' , 'id_tipo_cliente' )->get();
							$cliente_inicial = $this->intersectRecords( $clients_inicial , $clients_final );
							$solicitud_tipo_cliente = array_unique( $clients_inicial->lists( 'id_tipo_cliente' ) );
							if ( count( array_intersect( $solicitud_tipo_cliente, $tipo_cliente_requerido ) ) >= 2 )
							{
								$cliente = '( ';
								foreach ( $cliente_inicial as $client_inicial )
									$cliente .= $client_inicial->{$client_inicial->clientType->relacion}->full_name .  ' , ' ; 
								$cliente = rtrim( $cliente , ', ' );
								$cliente .= ' ).';
								$msg .= '<h5>Las solicitudes ' . $this->solMsg( $solicitud_inicial , $solicitud_secundaria , $solicitud_final ) . ' ' . $tiempo->mensaje . ' ' . $cliente . '</h5>';
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

    private function solMsg( $record1 , $record2 , $record3 )
    {
    	return $record1->id . /*' ' . $record1->created_at .*/ ' , ' . $record2->id . ' ' . /*$record2->created_at . ' ' .*/ $record3->id . ' ' /*. $record3->created_at*/ ; 
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
				$msg .= '<h5>La solicitud N° ' .  $solicitud->id . $tiempo->mensaje . '</h5>';
			else if ( ( ! is_null( $lastExpense ) ) && $this->timeAlert( $lastExpense , 'diffInDays' , 'updated_at' ) >= $tiempo->valor )
				$msg .= '<h5>La solicitud N° ' .  $solicitud->id . ' ' .  $tiempo->mensaje . '</h5>';	
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

}