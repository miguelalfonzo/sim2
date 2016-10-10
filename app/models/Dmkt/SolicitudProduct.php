<?php

namespace Dmkt;
use \Eloquent;
use \DB;
use \Fondo\FondoGerProd;
use \Fondo\FondoSupervisor;
use \Fondo\FondoInstitucional;
use \Expense\ChangeRate;
use \Carbon\Carbon;

class SolicitudProduct extends Eloquent
{
    protected $table = TB_SOLICITUD_PRODUCTO;
    protected $primaryKey = 'id';

    public function lastId()
    {
        $lastId = SolicitudProduct::orderBy( 'id' , 'DESC' )->first();
        if( is_null( $lastId ) )
            return 0;
        else
            return $lastId->id;
    }

    public function getSubFondo( $userType , $solicitud , $productoId = null )
    {
        $id_producto = isset( $productoId ) ? $productoId : $this->id_producto;
        $year        = $solicitud->created_at_year; 
        if ( $userType == SUP )
        {
            $userId = $solicitud->personalTo->userSup();

            return FondoSupervisor::select( [ 'id' , 'subcategoria_id' , 'saldo' , 'retencion' , 'marca_id' , '\'S\' tipo' ] )
                    ->whereHas( 'subcategoria' , function( $query ) use ( $userType )
                    {
                        $query->where( 'trim( tipo )' , $userType );
                    })
                    ->with( 'subCategoria.categoria' , 'marca' )
                    ->where( 'supervisor_id' , $userId )
                    ->where( 'marca_id' , $id_producto )
                    ->where( 'anio' , $year )
                    ->get();
        }
        else if( in_array( $userType , [ GER_PROD , GER_PROM ] ) )
        {
            return FondoGerProd::select( [ 'id' , 'subcategoria_id' , 'saldo' , 'retencion' , 'marca_id' , '\'P\' tipo' ] )
                    ->whereHas( 'subcategoria' , function( $query ) use ( $userType )
                    {
                        $query->where( 'trim( tipo )' , $userType );
                    })
                    ->with( 'subCategoria.categoria' , 'marca' )
                    ->where( 'marca_id' , $id_producto )
                    ->where( 'anio' , $year )
                    ->where( 'saldo' , '>' , 0 )
                    ->get();
        }
        else if( in_array( $userType , [ GER_COM , GER_GER ] ) )
        {
            $supFunds = FondoSupervisor::select( [ 'id' , 'subcategoria_id' , 'saldo' , 'retencion' , 'marca_id' , '\'S\' tipo' ] )
                        ->whereHas( 'subcategoria' , function( $query ) use ( $userType )
                        {
                            $query->where( 'trim( tipo )' , SUP );
                        })
                        ->with( 'subCategoria.categoria' , 'marca' )
                        ->where( 'marca_id' , $id_producto )
                        ->where( 'anio' , $year )
                        ->where( 'saldo' , '>' , 0 )
                        ->get();
            $gerFunds = FondoGerProd::select( [ 'id' , 'subcategoria_id' , 'saldo' , 'retencion' , 'marca_id' , '\'P\' tipo' ] )
                        ->whereHas( 'subcategoria' , function( $query ) use ( $userType )
                        {
                            $query->whereIn( 'trim( tipo )' , [ GER_PROD , GER_PROM ] );
                        })
                        ->with( 'subCategoria.categoria' , 'marca' )
                        ->where( 'marca_id' , $id_producto )
                        ->where( 'anio' , $year )
                        ->where( 'saldo' , '>' , 0 )
                        ->get();
            return $supFunds->merge( $gerFunds );
        }
        else
        {
            return [];
        }
    }

    /*protected function getSolProducts( $idSolProduct )
    {
        return SolicitudProduct::whereIn( 'id' , $idSolProduct )->lists( 'id_producto' );
    }*/

    public function thisSubFondo()
    {
        if ( $this->id_tipo_fondo_marketing == GER_PROD )
            return $this->belongsTo( 'Fondo\FondoGerProd' , 'id_fondo_marketing' );
        else
            return $this->belongsTo( 'Fondo\FondoSupervisor' , 'id_fondo_marketing' );
    }

    public function fondoSup()
    {
        return $this->belongsTo( 'Fondo\FondoSupervisor' , 'id_fondo_marketing' );
    }

    public function fondoGerProd()
    {
        return $this->belongsTo( 'Fondo\FondoGerProd' , 'id_fondo_marketing' );
    }


    public function marca()
    {
        return $this->hasOne( 'Dmkt\Marca' , 'id' , 'id_producto' );
    }

    public function fondoMarca()
    {
        return $this->hasOne( 'Dmkt\Marca' , 'id' , 'id_fondo_producto' );
    }

    public function subCatFondo()
    {
        return $this->hasOne( 'Fondo\FondoSubCategoria' , 'id' , 'id_fondo' );
    }

    public function user()
    {
        return $this->hasOne( 'User' , 'id' , 'id_fondo_user');
    }

    protected function getMontoAsignadoSolesAttribute()
    {
        $compra = ChangeRate::getLastDayDolar( $this->updated_at );
        return round( $this->monto_asignado * $compra , 2 , PHP_ROUND_HALF_DOWN );
    }

    protected function setMontoAsignadoAttribute( $value )
    {
        $this->attributes[ 'monto_asignado' ] = round( $value , 2 , PHP_ROUND_HALF_DOWN );
    }

}