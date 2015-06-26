<?php

namespace Alert;

use \BaseController;
use \Exception;
use \Carbon\Carbon;
use \Dmkt\Solicitud;
use \Auth;

class AlertController extends BaseController
{
	public function alertControl()
	{

	}

	public function clientAlert()
	{
		$solicituds = Solicitud::all();
		\Log::error( $solicituds->count() );
		foreach ( $solicituds as $key => $solicitud )
		{
			$clients = $solicitud->clients;
			$solicitud_tipo_cliente = array_unique( $clients->lists( 'id_tipo_cliente') );
			$tipo_cliente_requerido = array( MEDICO , INSTITUCION );
			if ( count( array_intersect( $solicitud_tipo_cliente, $tipo_cliente_requerido ) ) <= 1 )
				unset( $solicituds[ $key ] );
		}
		\Log::error( $solicituds->count() );
		$clientList = array();
		foreach( $solicituds as $solicitud_inicial )
		{
			foreach ( $solicituds as $solicitud_secundaria )
			{
				$first_merge = $solicitud_inicial->clients->merge( $solicitud_secundaria->clients );

				/*if ( $solicitud_inicial->id != $solicitud_final->id )
				{
					foreach ( $solicituds as $solicitud_final )
					{
						if ( $solicitud_inicial->id != $solicitud_final->id || $solicitud_secundaria != $solicitud_final->id )
						{

						}
					}
				}*/
			}
		}
	}

	public function expenseAlert()
	{
		$solicituds = Solicitud::where( 'id_user_assign' , Auth::user()->id )->where( 'id_estado' , GASTO_HABILITADO )->get();
		$msg = '';
		\Log::error( json_encode( $solicituds ) );
		foreach ( $solicituds as $solicitud )
		{
			$expenseHistory = $solicitud->expenseHistory;
			$lastExpense = $solicitud->lastExpense;
			\Log::error( json_encode( $lastExpense ) );
			if ( is_null( $lastExpense ) && $this->timeAlert( $expenseHistory , 'diffInWeeks' ) >= 1 )
				$msg .= 'No se ha rendido cuenta de la solicitud N° ' .  $solicitud->id . ' por mas de 1 semana desde que se habilito el registro de gastos. ';
			else if ( $this->timeAlert( $lastExpense , 'diffInWeeks' ) >= 1 )
				$msg .= 'No se ha rendido cuenta de la solicitud N° ' .  $solicitud->id . ' por mas de 1 semana desde que se registro el ultimo documento. ';	
		}
		return array( 'color' => 'darkred' , 'msg' => $msg );
	}

	public function timeAlert( $record , $method )
	{
		\Log::error( json_encode( $record ) );
		$now = Carbon::now();
		$updated = new Carbon( $record->updated_at );
		return $updated->$method( $now );
	}

	public function compareTime( $record , $method )
	{
		if ( $this->timeAlert( $record , $method) >= 1 )
			return array( 'color' => 'darkred' , 'msg' => 'No se ha registrado los gastos de la solicitud por mas de 1 mes.' );
		else
			return array();
	}

}