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
use \Exception;
use \System\FondoHistory;
use \Dmkt\Solicitud;
use \Carbon\Carbon;

class MoveController extends BaseController
{
 public function __construct()
    {
        parent::__construct();
        $this->beforeFilter('active-user');
    }

    private function searchMove( $date )
    {
    	if ( empty( $date ) )
            return $this->warningException('El campo fecha se encuentra vacio' ,__FUNCTION__ , __LINE__ , __FILE__ );
    
        $dates = $this->setDates( $date );

        $middleRpta = $this->userType();

        if ($middleRpta[ status ] == ok)
        {
            $middleRpta = $this->searchSolicituds( R_FINALIZADO , $middleRpta[data] , $dates['start'] , $dates['end']);
            if ($middleRpta[status] == ok)
            {
                foreach ( $middleRpta[data] as $solicitud )
                { 
                    $detalle = $solicitud->detalle;
                    $jDetalle = json_decode($detalle->detalle);
                    $deposito = $detalle->deposit;
                    
                        if ( $detalle->id_moneda == DOLARES )
                            $solicitud->saldo = $detalle->typeMoney->simbolo . ' ' . ( $detalle->monto_actual - $solicitud->expenses->sum('monto') );
                        elseif ( $detalle->id_moneda == SOLES )
                            $solicitud->saldo = $detalle->typeMoney->simbolo . ' ' . ( $detalle->monto_actual - $solicitud->expenses->sum('monto') );
                        else
                            $solicitud->saldo = 'El Tipo de Moneda es: '.$detalle->id_moneda ;
                }
                $view = View::make('Tables.movimientos')->with( array( 'solicituds' => $middleRpta[data] ) )->render();
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
                return $this->warningException( substr($this->msgValidator($validator), 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
            
            $middleRpta = $this->getDocs( $inputs['idProof'] , $inputs['date_start'] , $inputs['date_end'] , $inputs['val'] );
            if ( $middleRpta[status] == ok )
                return $this->setRpta( View::make('Dmkt.Cont.list_documents')->with( 'proofs' , $middleRpta[data] )->render() );
            else
                return $middleRpta;    
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function getDocs( $idProof , $start , $end , $val )
    {
        $documents = Expense::orderBy( 'updated_at' , 'desc');
        $documents->where( 'idcomprobante' , $idProof );

        if ( ! empty( trim( $val ) ) )
            $documents->where( function ( $q ) use ( $val )
            {
                if ( is_numeric( $val) )
                    $q->where( 'num_prefijo' , $val )->orWhere( 'num_serie' , $val )->orWhere( 'RUC' , $val )->orWhere( 'UPPER( razon )' , 'like' , '%strtoupper( $val )%' );
                $q->orWhere( 'UPPER( razon )' , 'like' , '%'.strtoupper( $val ).'%' );
            });
            
        $documents->whereRaw( "fecha_movimiento between to_date('$start','DD/MM/YYYY') and to_date('$end','DD/MM/YYYY') ");
        return $this->setRpta( $documents->get() );
    }

    public function getTable()
    {
        try
        {
            $inputs = Input::all();
            switch( $inputs['type'] )
            {
                case 'solicitudes':
                    return $this->searchSim( $inputs );
                case 'estado-fondos':
                    return $this->getFondoReport( $inputs['date'] );
                case 'movimientos':
                    return $this->searchMove( $inputs['date'] );
            }
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function searchSim( $inputs )
    {
        $today = getdate();
        $m = $today['mday'] . '-' . $today['mon'] . '-' . $today['year'];      
        if ( Input::has('idstate'))
            $estado = $inputs['idstate'];
        else
            $estado = R_TODOS;
        if ( Input::has('date_start'))
            $start = $inputs['date_start'];
        else
            $start = date('01-m-Y', strtotime($m));
        if (Input::has('date_end'))
            $end = $inputs['date_end'];
        else
            $end = date('t-m-Y', strtotime($m));
        $middleRpta = $this->userType();
        if ( $middleRpta[status] == ok )
        {
            $middleRpta = $this->searchSolicituds( $estado , $middleRpta[data] , $start , $end );
            if ( $middleRpta[status] == ok )
            {
                $data = array( 'solicituds' => $middleRpta[data] );
                if ( Auth::user()->type == TESORERIA )
                    $data['tc'] = ChangeRate::getTc();
                return $this->setRpta( array( 'View' => View::make('template.List.solicituds')->with( $data )->render() ) );
            }
        }
        return $middleRpta;
    }

    private function formatAnioMes( $date )
    {
        return Carbon::createFromFormat( 'd/m/Y' , $date )->format( 'Ym' );
    }

    protected function searchSolicituds( $estado , $idUser , $start , $end )
    {
        $solicituds = Solicitud::where( function( $query ) use( $start , $end )
        {
            $query->where( function( $query ) use( $start , $end )
            {
                $query->where( 'idtiposolicitud' , SOL_REP )->whereRaw( "created_at between to_date('$start','DD-MM-YY') and to_date('$end','DD-MM-YY')+1" );
            })->orWhere( function( $query ) use( $start , $end )
            {
                $query->where( 'idtiposolicitud' , SOL_INST )->wherehas( 'detalle' , function ( $query ) use( $start , $end )
                {
                    $query->whereHas( 'periodo' , function( $query ) use( $start , $end )
                    {
                        $query->where( 'aniomes' , '>=' , $this->formatAnioMes( $start ) )->where( 'aniomes' , '<=' , $this->formatAnioMes( $end ) );
                    });
                });  
            });
        });
        
        if ( in_array( Auth::user()->type , array ( REP_MED , SUP , GER_PROD , GER_PROM , ASIS_GER ) ) )
            $solicituds->where( function ( $query )
            {
                $query->where( function ( $query )
                {
                    $query->where( 'created_by' , '<>' , Auth::user()->id )->where( function ( $query )
                    {
                        $query->where( 'id_user_assign' , '<>' , Auth::user()->id )->orWhereNull( 'id_user_assign' );
                    })->whereHas( 'gerente' , function( $query )
                    {
                        $query->where( 'id_gerprod' , Auth::user()->id )->orWhere( 'id_gerprod' , Auth::user()->tempId() );
                    });
                })->orWhere( function ( $query )
                {
                    $query->where( 'created_by' , Auth::user()->id )->orWhere( function ( $query )
                    {
                        $query->where( 'created_by' , '<>' , Auth::user()->id )->where( 'id_user_assign' , Auth::user()->id );
                    });
                });
            });
        elseif ( Auth::user()->type == TESORERIA ) 
            if ( $estado != R_FINALIZADO)
                $solicituds->whereIn( 'id_estado' , array( DEPOSITO_HABILITADO , DEPOSITADO ) );

        if ( $estado != R_TODOS)
            $solicituds->whereHas( 'state' , function ( $q ) use( $estado )
            {
                $q->whereHas( 'rangeState' , function ( $t ) use( $estado )
                {
                    $t->where( 'id' , $estado );
                });
            });    
        
        $solicituds = $solicituds->orderBy('id', 'ASC')->get();     
        return $this->setRpta( $solicituds );
    }

    private function getFondoReport()
    {
        $fondoHistories = FondoHistory::orderBy( 'updated_at' , 'desc' )->get();
        return $this->setRpta( array( 'View' => View::make( 'Tables.fondo')->with( 'fondoHistories' , $fondoHistories )->render() ) );
    }

    public function getSolicitudDetail()
    {
        try
        {
            $inputs = Input::all();
            $solicitud = Solicitud::find( $inputs[ 'id_solicitud'] );
            $data = array( 'solicitud' => $solicitud , 'politicStatus' => false , 'detalle' => $solicitud->detalle , 'view' => true );
            return $this->setRpta( array( 'View' => View::make( 'Dmkt.Solicitud.Tab.tabs' )->with( $data )->render() ) );
            
            if ( $solicitud->idtiposolicitud == SOL_REP )
                return $this->setRpta( array( 'View' => View::make( 'Dmkt.Solicitud.Representante.detail' )->with( $data )->render() ) );
            else
                return $this->setRpta( array( 'View' => View::make( 'Dmkt.Solicitud.Institucional.detail' )->with( $data )->render() ) );
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    public function getStatement(){
        return View::make('template.tb_estado_cuenta');
    }
}