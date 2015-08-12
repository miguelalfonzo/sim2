<?php

namespace Fondo;

use \BaseController;
use \System\FondoMktHistory;
use \Auth;
use \View;
use \Input;

class FondoMkt extends BaseController
{

	public function setFondoMktHistories( $historiesFondoMkt , $idSolicitud )
    {
        foreach( $historiesFondoMkt as $historyFondoMkt )
            $this->setFondoMktHistory( $historyFondoMkt , $idSolicitud );
    }

    public function setFondoMktHistory( $historyFondoMkt , $idSolicitud )
    {
        $fondoMktHistory                          = new FondoMktHistory;
        $fondoMktHistory->id                      = $fondoMktHistory->nextId();
        $fondoMktHistory->id_solicitud            = $idSolicitud;
        $fondoMktHistory->id_to_fondo             = $historyFondoMkt[ 'idFondo' ];
        $fondoMktHistory->id_tipo_to_fondo        = $historyFondoMkt[ 'idFondoTipo' ];
        $fondoMktHistory->id_fondo_history_reason = $historyFondoMkt[ 'reason' ];
        $fondoMktHistory->to_old_saldo            = $historyFondoMkt[ 'oldSaldo' ];
        $fondoMktHistory->to_new_saldo            = $historyFondoMkt[ 'newSaldo' ];
        $fondoMktHistory->to_old_saldo_neto       = $historyFondoMkt[ 'oldSaldoNeto' ];
        $fondoMktHistory->to_new_saldo_neto       = $historyFondoMkt[ 'newSaldoNeto' ];
        $fondoMktHistory->save();
    }

    public function decreaseFondoInstitucional( $fondo , $montoTotal )
    {
            $fondo->saldo_neto -= $montoTotal;
            $fondo->save();
    }

    public function validateBalance( $userTypes , $fondos )
    {
        $userTypes = array_unique( $userTypes );
        if ( count( $userTypes) != 1 )
            return $this->warningException( 'No es posible asignar Fondos de Roles Diferentes' , __FUNCTION__ , __LINE__ , __FILE__ );
        else
            $userType = $userTypes[ 0 ];

        if ( $userType == SUP )
            $fondoCategoria = array_unique( FondoSupervisor::whereIn( 'id' , array_keys( $fondos ) )->lists( 'subcategoria_id' ) );
        elseif ( $userType == GER_PROD )
            $fondoCategoria = array_unique( FondoGerProd::whereIn( 'id' , array_keys( $fondos ) )->lists( 'subcategoria_id' ) );

        if ( count( $fondoCategoria ) != 1 )
            return $this->warningException( 'No es posible seleccionar Fondos de Diferentes SubCategorias por Solicitud' , __FUNCTION__ , __LINE__ , __FILE__ );
        
        $msg = ' el cual no es suficiente para completar el registro , se requiere un saldo de S/.';
        $middleRpta = $this->validateFondoSaldo( $fondos , $userType , $msg , '_neto' );
        
        if ( $middleRpta[ status ] == ok )
            return $this->setRpta( $userType );

        return $middleRpta;
    }

    public function discountBalance( $ids_fondo , $moneda , $tc , $idSolicitud , $userType = NULL )
    {
        if ( $moneda == SOLES )
            $tasaCompra = 1;
        elseif ( $moneda == DOLARES )
            $tasaCompra = $tc->compra;

        $historiesFondoMkt = array();
        foreach( $ids_fondo as $id_fondo )
        {
            if ( ! is_null( $id_fondo[ 'old' ] ) )
            {
                if ( $id_fondo[ 'oldUserType' ] == SUP )
                    $fondoMkt = FondoSupervisor::find( $id_fondo[ 'old' ] );
                else
                    $fondoMkt = FondoGerProd::find( $id_fondo[ 'old' ] );
                $this->setHistoryData( $historiesFondoMkt , $fondoMkt , $tasaCompra , $id_fondo[ 'oldMonto'] , $id_fondo[ 'oldUserType' ] , FONDO_LIBERACION );
                
            }
            if ( ! is_null( $userType ) )
            {
                if ( $userType == SUP )
                    $fondoMkt = FondoSupervisor::find( $id_fondo[ 'new' ] );
                else
                    $fondoMkt = FondoGerProd::find( $id_fondo[ 'new' ] );
                $this->setHistoryData( $historiesFondoMkt , $fondoMkt , $tasaCompra , $id_fondo[ 'newMonto' ] , $userType , FONDO_RETENCION );            
            }
        }
        \Log::error( $historiesFondoMkt );
        $this->setFondoMktHistories( $historiesFondoMkt , $idSolicitud ); 
    }

    public function validateFondoSaldo( $fondosData , $fondoType , $msg , $tipo = '' )
    {
        foreach( $fondosData as $idFondo => $fondoMonto )
        {
            if ( $fondoType == SUP )
                $fondo = FondoSupervisor::find( $idFondo );
            elseif ( $fondoType == GER_PROD )
                $fondo = FondoGerProd::find( $idFondo );

            if ( $fondo->{ 'saldo' . $tipo } < 0 ){
                return $this->warningException( 'El Fondo ' .  $this->fondoName( $fondo ) . ' solo cuenta con S/.' . ( $fondo->{ 'saldo' . $tipo } + $fondoMonto ) . 
                                                $msg . $fondoMonto . ' en total' , __FUNCTION__ , __FILE__ , __LINE__ );
            }
        }
        return $this->setRpta();
    }

    public function setFondo( $fondoData , $solProduct , $detalle , $tc , &$userTypes , &$fondos )
    {
        $fondoData = explode( ',' , $fondoData );

        if(  is_null( $solProduct->id_fondo_marketing ) )
        {
            if ( ! ( Auth::user()->type == SUP || Auth::user()->type == GER_PROD ) )
            {
                if( $fondoData[ 1 ] == SUP && ! in_array( Auth::user()->type , array( SUP , GER_PROM ) ) )
                    return $this->warningException( 'Cancelado - No existe un fondo asignado y el usuario no puede asignar fondos' , __FUNCTION__ , __LINE__ , __FILE__ );
                else if( $fondoData[ 1 ] == GER_PROD && ! in_array( Auth::user()->type , array( GER_PROD , GER_COM ) ) )
                    return $this->warningException( 'Cancelado - No existe un fondo asignado y el usuario no puede asignar fondos' , __FUNCTION__ , __LINE__ , __FILE__ );
            }
        }

        if ( $fondoData[ 1 ] == SUP )
            $fondoMkt = FondoSupervisor::find( $fondoData[ 0 ] );
        elseif ( $fondoData[ 1 ] == GER_PROD )
            $fondoMkt = FondoGerProd::find( $fondoData[ 0 ] );

        if ( $detalle->id_moneda == DOLARES )
            $monto_soles = round( $solProduct->monto_asignado * $tc->compra , 2 , PHP_ROUND_HALF_DOWN );
        elseif ( $detalle->id_moneda == SOLES )
            $monto_soles = $solProduct->monto_asignado;

        if ( isset( $fondos[ $fondoMkt->id ] ) )
            $fondos[ $fondoMkt->id ] += $monto_soles;
        else
            $fondos[ $fondoMkt->id ] = $monto_soles;
        
        $userTypes[]                         = $fondoData[ 1 ];
        $solProduct->id_fondo_marketing      = $fondoData[ 0 ];
        $solProduct->id_tipo_fondo_marketing = $fondoData[ 1 ];
        return $this->setRpta();
    }

    private function setHistoryData( &$historyFondoMkt , $subFondo , $tasaCompra , $monto , $userType , $reason )
    {
        $oldSaldo     = $subFondo->saldo;
        $oldSaldoNeto = $subFondo->saldo_neto;
        if ( $reason == FONDO_LIBERACION )
            $subFondo->saldo_neto += $monto * $tasaCompra ;
        elseif ( $reason == FONDO_RETENCION )
            $subFondo->saldo_neto -= $monto * $tasaCompra ;
            
        $subFondo->save();
        $historyFondoMkt[] = array( 
            'idFondo'      => $subFondo->id , 
            'idFondoTipo'  => $userType ,
            'oldSaldo'     => $oldSaldo , 
            'oldSaldoNeto' => $oldSaldoNeto , 
            'newSaldo'     => $subFondo->saldo , 
            'newSaldoNeto' => $subFondo->saldo_neto , 
            'reason'       => $reason 
        );
    }

    public function getFondoHistorial()
    {
        $data = array( 
            'fondoSubCategories' => FondoSubCategoria::order()
        );
        return View::make( 'Tables.fondo_mkt_history' , $data );
    }

    public function getFondoSubCategoryHistory()
    {
        $inputs = Input::all();
        $start = $inputs[ 'start' ];
        $end   = $inputs[ 'end' ];  
        //$fondoSubCategory = FondoSubCategoria::find( 1 );
        //$fondos = $fondoSubCategory->fondos;
        $subCategory = FondoSubCategoria::find( $inputs[ 'id_subcategoria' ] );
        \Log::error( $subCategory->toJson() );
        
        $subCategoryType = $subCategory->fondoMktType;
        \Log::error( $subCategoryType->toJson() );
        
        $fondoMktHistories = FondoMktHistory::whereRaw( "created_at between to_date( '$start' , 'DD-MM-YY' ) and to_date( '$end' , 'DD-MM-YY' ) + 1" )
                                ->where( 'id_tipo_to_fondo' , $subCategory->tipo )
                                ->whereHas( $subCategoryType->relacion , function( $query ) use ( $subCategory )
                                {
                                    $query->where( 'subcategoria_id' , $subCategory->id );
                                })->get();
        \Log::error( json_encode( $fondoMktHistories ) );
        $data = array( 
            'FondoMktHistories' => $fondoMktHistories 
            );
        return $this->setRpta( array( 'View' => View::make( 'Tables.table_fondo_mkt_history' , $data )->render() ) );
    
    }

}
