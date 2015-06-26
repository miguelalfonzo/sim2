<?php

namespace Alert;

use \BaseController;
use \Exception;
use \Carbon\Carbon;
use \Dmkt\Solicitud;
use \Auth;

class AlertController extends BaseController
{

	public function expenseAlert()
	{
		$solicituds = Solicitud::where( 'id_user_assign' , Auth::user()->id )->where( 'id_estado' , GASTO_HABILITADO )->get();
		\Log::error( json_encode( $solicituds ) );
		$msg = '';
		foreach ( $solicituds as $solicitud )
		{
			$expenseHistory = $solicitud->expenseHistory;
			if ( ! is_null( $expenseHistory ) )
				if ( count( $this->timeAlert( $expenseHistory , 'diffInWeeks' ) ) >= 1 )
					$msg .= 'No se ha rendido cuenta de la solicitud N° ' .  $solicitud->id . ' por mas de 1 semana desde que se habilito el registro de gastos. ';
		}
		\Log::error( $msg );
		return array( 'color' => 'darkred' , 'msg' => $msg );
	}

	public function timeAlert( $solicitud , $method )
	{
		$now = Carbon::now();
		$created = new Carbon( $solicitud->created_at );
		if ( $created->$method( $now ) >= 1 )
			return array( 'color' => 'darkred' , 'msg' => 'No se ha rendido cuenta de la solicitud por mas de 1 mes' );
		return array();
	}

}