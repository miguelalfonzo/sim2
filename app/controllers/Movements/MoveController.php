<?php

namespace Movements;

use \BaseController;
use \Input;
use \Log;
use \DateTime;
use \Auth;
use \View;
use \Expense\ChangeRate;
use \Expense\ProofType;
use \Expense\Expense;
use \Validator;

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

    public function searchDocs()
    {
        try
        {
            $inputs = Input::all();
            $rules = array( 'date_start' => 'required|date_format:"d/m/Y"' , 'date_end' => 'required|date_format:"d/m/Y"' );
            $validator = Validator::make( $inputs , $rules );
            if ( $validator->fails() ) 
                return $this->warningException( __FUNCTION__ , substr($this->msgValidator($validator), 0 , -1 ) );
            else
            {
                $middleRpta = $this->getDocs( $inputs['idProof'] , $inputs['date_start'] , $inputs['date_end'] , $inputs['val'] );
                if ( $middleRpta[status] == ok )
                    return $this->setRpta( View::make('Dmkt.Cont.list_documents')->with( 'proofs' , $middleRpta[data] )->render() );
                else
                    return $middleRpta;
            }
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function getDocs( $idProof , $start , $end , $val )
    {
        try
        {
            $documents = Expense::orderBy( 'updated_at' , 'desc');
            $documents->where( 'idcomprobante' , $idProof );

            if ( is_numeric( $val) )
                $documents->where( function ( $q ) use ( $val )
                {
                    $q->where( 'num_prefijo' , $val )->orWhere( 'num_serie' , $val )->orWhere( 'RUC' , $val )->orWhere( 'UPPER( razon )' , 'like' , '%strtoupper( $val )%' );
                });
            else
                $documents->where( 'UPPER( razon )' , 'like' , '%'.strtoupper( $val ).'%' );
            $documents->whereRaw( "fecha_movimiento between to_date('$start','DD-MM-YY') and to_date('$end','DD-MM-YY')");
            return $this->setRpta( $documents->get() );
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }
}