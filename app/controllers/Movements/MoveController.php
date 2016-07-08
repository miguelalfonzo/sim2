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
use \Fondo\FondoSubCategoria;
use \Session;

class MoveController extends BaseController
{
    
    public function __construct()
    {
        parent::__construct();
        $this->beforeFilter('active-user');
    }

    private function searchMove( $start , $end , $subCategoriaId )
    {
    	
        $dates = $this->setDates( $start , $end );
        $data = $this->searchSolicituds( R_TODOS , $dates , $subCategoriaId , 'MOVIMIENTOS' );
        
        foreach ( $data as $solicitud )
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
        $view = View::make('Tables.movimientos')->with( array( 'solicituds' => $middleRpta[data] , 'subCategoriaId' =>$subCategoriaId ) )->render();
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
        return $middleRpta;
    }

    private function setDates( $start , $end )
    {
        $dates = array(
            'start' => Carbon::createFromFormat( 'd/m/Y' , $start )->format( '01/m/Y' ) ,
            'end'   => Carbon::createFromFormat( 'd/m/Y' , $end )->format( 't/m/Y' )
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
            
            $middleRpta = $this->getDocs( $inputs[ 'idProof' ] , $inputs[ 'date_start' ] , $inputs[ 'date_end' ] , $inputs[ 'val' ] );
        
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
        {
            $documents->where( function ( $q ) use ( $val )
            {
                if ( is_numeric( $val) )
                    $q->where( 'num_serie' , 'like' , '%' . $val . '%' )->orWhere( 'RUC' , 'like' , '%' . $val . '%' );
                $q->orWhere( 'UPPER( razon )' , 'like' , '%' . mb_strtoupper( $val ) . '%' )->orWhere( 'UPPER( num_prefijo )' , 'LIKE' , '%' . mb_strtoupper( $val ) . '%' );
            });
        }
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
                case 'movimientos':
                    return $this->searchMove( $inputs['date_start' ] , $inputs[ 'date_end' ] , $inputs[ 'filter' ] );
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
        
        if ( Input::has( 'idstate' ) )
        {
            $estado = $inputs['idstate'];
        }
        else
        {
            $estado = R_TODOS;
        }

        if ( Input::has('date_start'))
        {
            $start = $inputs['date_start'];
        }
        else
        {
            $start = Carbon::now()->format( '01-m-Y' );
            //$start = date('01-m-Y', strtotime($m));
        }

        if (Input::has('date_end'))
        {
            $end = $inputs['date_end'];
        }
        else
        {
            $end = Carbon::now()->format( 'd-m-Y' );
            //$end = date('t-m-Y', strtotime($m));
        }

        $dates = array( 'start' => $start , 'end' => $end );    

        $data = $this->searchSolicituds( $estado , $dates , null );
        
        $data = array( 'solicituds' => $data );
        if ( Auth::user()->type == TESORERIA )
            $data['tc'] = ChangeRate::getTc();
        Session::put( 'state' , $estado );
        $view = array( 'View' => View::make('template.List.solicituds')->with( $data )->render() );
        return $this->setRpta( $view  );
        
        return $middleRpta;
    }

    private function formatAnioMes( $date )
    {
        return Carbon::createFromFormat( 'd/m/Y' , $date )->format( 'Ym' );
    }

    protected function searchSolicituds( $estado , array $dates , $filter , $type = 'FLUJO' )
    {
        $solicituds = Solicitud::where( function( $query ) use( $dates )
        {
            $query->where( function( $query ) use( $dates )
            {
                $query->where( 'idtiposolicitud' , '<>' , SOL_INST )->whereRaw( "created_at between to_date( '" . $dates[ 'start' ] . "','DD-MM-YY') and to_date( '" . $dates[ 'end' ] . "' ,'DD-MM-YY')+1" );
            })->orWhere( function( $query ) use( $dates )
            {
                $query->where( 'idtiposolicitud' , SOL_INST )->wherehas( 'detalle' , function ( $query ) use( $dates )
                {
                    $query->whereHas(TB_PERIODO, function( $query ) use( $dates )
                    {
                        $query->where( 'aniomes' , '>=' , $this->formatAnioMes( $dates[ 'start' ] ) )->where( 'aniomes' , '<=' , $this->formatAnioMes( $dates[ 'end' ] ) );
                    });
                });  
            });
        });
        if ( $type == 'FLUJO' )
        {
            if ( in_array( Auth::user()->type , array ( REP_MED , SUP , GER_PROD , GER_PROM , ASIS_GER ) ) )
                $solicituds->where( function ( $query )
                {
                    $query->whereHas( 'gerente' , function( $query )
                    {
                        $query->whereIn( 'id_gerprod' , array( Auth::user()->id , Auth::user()->tempId() ) );
                    })->orWhereIn( 'created_by' , array( Auth::user()->id , Auth::user()->tempId() ) )
                    ->orWhereIn( 'id_user_assign' , array( Auth::user()->id , Auth::user()->tempId() ) );
                });
            elseif ( Auth::user()->type == TESORERIA ) 
                if ( $estado == R_REVISADO )
                    $solicituds->whereIn( 'id_estado' , array( DEPOSITO_HABILITADO , DEPOSITADO ) );
                else if( $estado == R_GASTO )
                    $solicituds->where( 'id_estado' , ENTREGADO );
        }
        elseif( $type == 'MOVIMIENTOS' )
        {
            if ( in_array( Auth::user()->type , array ( SUP ) ) )
            {
                $solicituds->where( function ( $query )
                {
                    $query->whereHas( 'gerente' , function( $query )
                    {
                        $query->whereIn( 'id_gerprod' , array( Auth::user()->id , Auth::user()->tempId() ) )->where( 'tipo_usuario' , SUP );
                    })->orWhereIn( 'created_by' , array( Auth::user()->id , Auth::user()->tempId() ) )
                    ->orWhereIn( 'id_user_assign' , array( Auth::user()->id , Auth::user()->tempId() ) )
                    ->orWhereIn( 'created_by' , Auth::user()->personal->employees->lists( 'user_id' ) )
                    ->orWhereIn( 'id_user_assign' , Auth::user()->personal->employees->lists( 'user_id' ) );
                });
            }
            elseif( in_array( Auth::user()->type , array( REP_MED ) ) ) 
            {
                $solicituds->where( 'id_user_assign' , Auth::user()->id );
            }

            if ( $filter != 0 )
            {
                $solicituds->where( function ( $query ) use( $filter )
                {
                    $query->where( 'idtiposolicitud' , SOL_REP )->whereHas( 'products' , function( $query ) use( $filter )
                    {
                        $query->where( function( $query ) use( $filter )
                        {
                            $query->where( 'id_tipo_fondo_marketing' , SUP )->whereHas( 'fondoSup' , function( $query ) use( $filter )
                            {
                                $query->where( 'subcategoria_id' , $filter );
                            })->orWhere( function( $query ) use( $filter )
                            {
                                $query->where( 'id_tipo_fondo_marketing' , GER_PROD )->whereHas( 'fondoGerProd' , function( $query) use( $filter )
                                {
                                    $query->where( 'subcategoria_id' , $filter );
                                });
                            });
                        });
                    })->orWhere( function( $query ) use( $filter )
                    {
                        $query->where( 'idtiposolicitud' , SOL_INST )->whereHas( 'detalle' , function( $query ) use( $filter )
                        {
                            $query->whereHas( 'thisSubFondo' , function( $query ) use( $filter )
                            {
                                $query->where( 'subcategoria_id' , $filter );
                            });
                        });
                    });
                });
            }
        }

        if ( $estado != R_TODOS )
            $solicituds->whereHas( 'state' , function ( $q ) use( $estado )
            {
                $q->whereHas( 'rangeState' , function ( $t ) use( $estado )
                {
                    $t->where( 'id' , $estado );
                });
            });
        $solicituds->with( 'activity' );
        return $solicituds->orderBy('id', 'ASC')->get();
    }

    public function getSolicitudDetail()
    {
        try
        {
            $inputs    = Input::all();
            $solicitud = Solicitud::find( $inputs[ 'id_solicitud'] );
            $data      = array(
                'solicitud'     => $solicitud, 
                'politicStatus' => false, 
                'detalle'       => $solicitud->detalle, 
                'view'          => true  );
            return $this->setRpta( array(
                'View' => View::make( 'Dmkt.Solicitud.Tab.tabs' )->with( $data )->render() )
            );
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    public function getStatement()
    {
        if ( in_array( Auth::user()->type , array( REP_MED , GER_PROM , GER_COM , CONT ) ) )
            $fondos = FondoSubCategoria::all();
        elseif( in_array( Auth::user()->type , array( SUP , GER_PROD ) ) )
            $fondos = FondoSubCategoria::where( 'trim( tipo )' , Auth::user()->type )->get();
        
        return View::make( 'template.tb_estado_cuenta' , array( 'fondosMkt' => $fondos ) );
    }

    public function getSolicituds()
    {
        try
        {
            $inputs = Input::all();
            $dates  = [ 'start' => $inputs[ 'fecha_inicio' ] , 'end' => $inputs[ 'fecha_final' ] ];
            $data   = $this->searchUserSolicituds2( $inputs[ 'estado' ] , $dates , null );

            $columns =
                [
                    [ 'title' => '#' , 'data' => 'id' , 'className' => 'text-center' ],
                    [ 'title' => 'Solicitud' , 'data' => 'actividad_titulo' , 'width' => '15%' ],
                    [ 'title' => 'Solicitador por' , 'data' => 'solicitador' , 'className' => 'text-center' ],
                    [ 'title' => 'Fecha de Solicitud' , 'data' => 'fecha_creacion' , 'className' => 'text-center' ],
                    [ 'title' => 'Aprobado por' , 'data' => 'revisor' , 'className' => 'text-center' ],
                    [ 'title' => 'Fecha de Aprobación' , 'data' => 'fecha_revision' , 'className' => 'text-center' ],
                    [ 'title' => 'Monto' , 'data' => 'monto' , 'className' => 'text-center' ],
                    [ 'title' => 'Estado' , 'data' => 'estado' , 'className' => 'text-center' ],
                    [ 'title' => 'Tipo' , 'data' => 'tipo_solicitud' , 'className' => 'text-center' ],
                    [ 'title' => 'Edicion' , 'data' => 'opciones' , 'className' => 'text-center' , 'width' => '15%' ],
                    
                    //[ 'title' => 'Edicion' , 'defaultContent' => '<button class="btn btn-primary">Test</button>' ],
                    //[ 'title' => 'Test' , 'data' => 'id_inversion' ]
                ];
            $user = Auth::user();
            if( $user->type == GER_COM )
            {
                $columns[] = [ 'title' => 'X' , 'data' => 'aprobacion_masiva' , 'className' => 'text-center' , 'defaultContent' => '' ];
            }
            elseif( $user->type == CONT )
            {
                $columns[ 3 ] = [ 'title' => 'Fecha de Deposito' , 'data' => 'fecha_entrega' , 'className' => 'text-center' ];
                $columns[]    = [ 'title' => 'X' , 'data' => 'aprobacion_masiva' , 'className' => 'text-center' , 'defaultContent' => '' ];
            }
            elseif( $user->type == TESORERIA )
            {
                $columns[ 2 ] = [ 'title' => 'Responsable' , 'data' => 'responsable' , 'className' => 'text-center' ];
                $columns[ 3 ] = [ 'title' => 'Fecha de Deposito' , 'data' => 'fecha_entrega' , 'className' => 'text-center' ];
                $columns[ 4 ] = [ 'title' => 'Deposito' , 'data' => 'monto' , 'className' => 'text-center' ];
                unset( $columns[ 5 ] , $columns[ 6 ] );
                $columns = array_values( $columns );
                      
            }
            
            //$data = Solicitud::select( 'id , titulo, id_inversion' )->get();

            $rpta = $this->setRpta( $data );
            $rpta[ 'usuario' ] = [ 'id' => $user->id , 'tipo' => $user->type ];
            $rpta[ 'usuario_temporal' ] = [ 'id' => $user->tempId() , 'tipo' => $user->tempType() ];
            $now = Carbon::now();
            $rpta[ 'now' ] = [ 'year' => $now->year , 'month' => $now->month , 'day' => $now->day ];
            $rpta[ 'columns' ] = $columns;
            return $rpta;



        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    protected function searchUserSolicituds2( $estado , array $dates , $filter , $type = 'FLUJO' )
    {
        return Solicitud::getUserSolicituds();
      
    }

    protected function searchUserSolicituds( $estado , array $dates , $filter , $type = 'FLUJO' )
    {
        \Log::info( microtime() );
        
        $str = 'ltrim( regexp_substr( detalle , \'"monto_aprobado":([[:digit:]]*)\' ) , \'"monto_aprobado":\' ) valor_aprobado';
        \Log::info( $str );

        $solicituds = Solicitud::where( function( $query ) use( $dates )
        {
            $query->where( function( $query ) use( $dates )
            {
                $query->where( 'idtiposolicitud' , '<>' , SOL_INST )->whereRaw( "created_at between to_date( '" . $dates[ 'start' ] . "','DD-MM-YY') and to_date( '" . $dates[ 'end' ] . "' ,'DD-MM-YY')+1" );
            })->orWhere( function( $query ) use( $dates )
            {
                $query->where( 'idtiposolicitud' , SOL_INST )->wherehas( 'detalle' , function ( $query ) use( $dates )
                {
                    $query->whereHas( TB_PERIODO , function( $query ) use( $dates )
                    {
                        $query->where( 'aniomes' , '>=' , $this->formatAnioMes( $dates[ 'start' ] ) )->where( 'aniomes' , '<=' , $this->formatAnioMes( $dates[ 'end' ] ) );
                    });
                });  
            });
        });
   

   /*     if ( $estado != R_TODOS )
        {
            $solicituds->whereHas( 'state' , function ( $q ) use( $estado )
            {
                $q->whereHas( 'rangeState' , function ( $t ) use( $estado )
                {
                    $t->where( 'id' , $estado );
                });
            });
        }*/
/*
        if ( in_array( Auth::user()->type , array ( REP_MED , SUP , GER_PROD , GER_PROM , ASIS_GER ) ) )
                $solicituds->where( function ( $query )
                {
                    $query->whereHas( 'gerente' , function( $query )
                    {
                        $query->whereIn( 'id_gerprod' , array( Auth::user()->id , Auth::user()->tempId() ) );
                    })->orWhereIn( 'created_by' , array( Auth::user()->id , Auth::user()->tempId() ) )
                    ->orWhereIn( 'id_user_assign' , array( Auth::user()->id , Auth::user()->tempId() ) );
                });
            elseif ( Auth::user()->type == TESORERIA ) 
                if ( $estado == R_REVISADO )
                    $solicituds->whereIn( 'id_estado' , array( DEPOSITO_HABILITADO , DEPOSITADO ) );
                else if( $estado == R_GASTO )
                    $solicituds->where( 'id_estado' , ENTREGADO );
                */
        $solicituds->select( [ 'id' , 'titulo' , 'id_actividad' , 'id_user_assign' , 'created_at' , 'id_detalle' , 'id_estado' ] );
        $solicituds->with( [ 'activity' => function( $query )
        {
            $query->select( [ 'id' , 'nombre' , 'color' ] );
        } , 'personalTo' => function( $query )
        {
            $query->select( [ 'user_id' , 'nombres' , 'apellidos' , 'initcap( nombres || \' \' || apellidos ) full_name' ] );
        } , 'lastHistory' => function( $query )
        {
            $query->select( [ 'id_solicitud' , 'updated_by' , 'updated_at' ] )
                ->with( [ 'updatedPersonal' => function( $query )
                {
                    $query->select( [ 'user_id' , 'nombres' , 'apellidos' , 'initcap( nombres || \' \' || apellidos ) full_name' ] );
                }]);
        } , 'detalle' => function( $query )
        {
            $query->select( [ 'id' , 'detalle' , 'id_moneda' , 
                'ltrim( regexp_substr( detalle , \'"monto_aprobado":"{0,1}[[:digit:]]+(\.[[:digit:]]+){0,1}\' ) , \'"monto_aprobado":\' ) valor_aprobado',
                'ltrim( regexp_substr( detalle , \'"monto_aceptado":"{0,1}[[:digit:]]+(\.[[:digit:]]+){0,1}\' ) , \'"monto_aceptado":\' ) valor_aceptado',
                'ltrim( regexp_substr( detalle , \'"monto_solicitado":"{0,1}[[:digit:]]+(\.[[:digit:]]+){0,1}\' ) , \'"monto_solicitado":\' ) valor_solicitado' ] )
                ->with( [ 'typeMoney' => function( $query )
                {
                    $query->select( [ 'id' , 'simbolo' ] );
                }]);;
        } , 'state' => function( $query )
        {
            $query->select( [ 'id' , 'nombre' , 'id_estado' ] )
                ->with( [ 'rangeState' => function( $query )
                {
                    $query->select( [ 'id' , 'color' ] );
                }]);;
        }]);
        \Log::info( microtime() );
        $data = $solicituds->orderBy('id', 'ASC')->get();
        \Log::info( microtime() );
        return $data;   
    }

}