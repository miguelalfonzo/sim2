<?php

namespace Movements;

use \BaseController;
use \Input;
use \Log;
use \DateTime;
use \Auth;
use \View;
use \Expense\ChangeRate;

class MoveController extends BaseController
{
	public function __construct()
    {
        parent::__construct();
        $this->beforeFilter('active-user');
    }

    public function searchMove()
    {
    	$date = Input::get('date');
        if ( empty( $date ) )
            return $this->warningException('El campo fecha se encuentra vacio',__FUNCTION__,'Empty Date');
        else            
        {
            $dates = $this->setDates( $date );
            $middleRpta = $this->userType();
            if ($middleRpta[status] == ok)
            {
                $middleRpta = $this->searchSolicituds( R_FINALIZADO , $middleRpta[data] , $dates['start'] , $dates['end']);
                if ($middleRpta[status] == ok)
                {
                    foreach ( $middleRpta[data] as $solicitud )
                    { 
                        Log::error('1');
                        $detalle = $solicitud->detalle;
                        $jDetalle = json_decode($detalle->detalle);
                        $deposito = $detalle->deposit;
                        if ( $deposito->account->idtipomoneda == DOLARES )
                        {
                            if ( $detalle->idmoneda == DOLARES )
                            {
                                foreach( $solicitud->expenses as $expense )
                                    $expense->total = $expense->total * ChangeRate::getTcv( $expense->fecha_movimiento );
                                $solicitud->saldo = ( $jDetalle->monto_aprobado * $jDetalle->tcv ) - $solicitud->expenses->sum('total');
                            }
                            elseif ( $detalle->idmoneda == SOLES )
                                $solicitud->saldo = ( $jDetalle->monto_aprobado * $jDetalle->tcv ) - $solicitud->expenses->sum('total');
                        }
                        elseif ( $deposito->account->idtipomoneda == SOLES )
                        {
                            if ( $detalle->idmoneda == DOLARES )
                            {
                                foreach( $solicitud->expenses as $expense )
                                    $expense->total = $expense->total * ChangeRate::getTcv( $expense->fecha_movimiento );
                                $solicitud->saldo = $jDetalle->monto_aprobado - $solicitud->expenses->sum('total');
                            }
                            elseif ( $detalle->idmoneda == SOLES )
                                $solicitud->saldo = $jDetalle->monto_aprobado - $solicitud->expenses->sum('total');    
                        }
                    }
                    $view = View::make('template.list_estado_cuenta')->with( array( 'solicituds' => $middleRpta[data] ) )->render();
                    if ( Auth::user()->type == TESORERIA )
                    {
                        $soles = $middleRpta[data]->sum( function( $solicitud )
                        {
                            $deposito = $solicitud->detalle->deposit;
                            $moneda = $deposito->account->typeMoney;
                            if ( $moneda->id == SOLES )
                                return $solicitud->detalle->deposit->total;
                        });
                        $dolares = $middleRpta[data]->sum( function( $solicitud )
                        {
                            $deposito = $solicitud->detalle->deposit;
                            $moneda = $deposito->account->typeMoney;
                            if ( $moneda->id == DOLARES )
                                return $solicitud->detalle->deposit->total;
                        });
                        $middleRpta[data]['Total'] = array( 'Soles' => $soles , 'Dolares' => $dolares );
                    }
                    $middleRpta[data]['View'] = $view;
                }
            }
            return $middleRpta;
    	}
    }

    private function setDates($date)
    {
        $dates = array(
            'start' => (new DateTime('01-'.$date))->format('d/m/Y'),
            'end'   => (new DateTime('01-'.$date))->format('t/m/y')
        );
        return $dates;
    }
}