<?php

namespace Dmkt;
use \Eloquent;
use \Auth;
use \DB;
use \Fondo\FondoGerProd;
use \Fondo\FondoSupervisor;
use \Log;

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

    public function getSubFondo( $userType , $solicitud )
    {
        if ( $userType == SUP )
        {
            $userid = $solicitud->asigned_to->personal->rmSup->user_id;
            return DB::table(TB_FONDO_SUPERVISOR.' fs')
                        ->select("m.descripcion || ' | ' || fc.descripcion || ' | ' || fsc.descripcion descripcion" , 'fs.saldo - fs.retencion saldo_disponible' , 'fs.id' , 'fs.marca_id' , '\'S\' tipo' )
                        ->leftJoin(TB_FONDO_CATEGORIA_SUB.' fsc' , 'fsc.id' , '=' , 'fs.subcategoria_id' )
                        ->leftJoin(TB_FONDO_CATEGORIA.' fc' , 'fc.id' , '=' , 'fsc.id_fondo_categoria' )
                        ->leftJoin(TB_MARCAS_BAGO.' m' , 'fs.marca_id' , '=' , 'm.id' )
                        ->where('fs.saldo' , '>' , 0 )
                        ->where('fsc.tipo' , FONDO_SUBCATEGORIA_SUPERVISOR )
                        ->where('fs.supervisor_id' , $userid )->orderBy( 'm.descripcion' , 'asc' )
                        ->get();
        }
        else if( $userType == GER_PROD )
        {
            return DB::table( TB_FONDO_GERENTE_PRODUCTO.' f' )
            ->select( "m.descripcion || ' | ' || fc.descripcion || ' | ' || fsc.descripcion descripcion" , 'f.saldo - f.retencion saldo_disponible', 'f.id' , 'f.marca_id' , '\'P\' tipo' )
            ->leftJoin( TB_FONDO_CATEGORIA_SUB.' fsc' , 'f.subcategoria_id' , '=' , 'fsc.id' )
            ->leftJoin( TB_FONDO_CATEGORIA.' fc' , 'fsc.id_fondo_categoria' , '=' , 'fc.id' )
            ->leftJoin( TB_MARCAS_BAGO.' m' , 'f.marca_id' , '=' , 'm.id' )
            ->where( function( $query ) use( $userType )
            {
                if ( $userType == GER_PROD )
                    $query->where( 'fsc.tipo' , FONDO_SUBCATEGORIA_GERPROD );
                else
                    $query->where( 'fsc.tipo' , 'NNN' );
            })->where( 'f.saldo' , '>' , 0 )->orderBy( 'm.descripcion' , 'asc' )->get();
        }
        else if( $userType == ASIS_GER )
        {
            return DB::table( TB_FONDO_INSTITUCION.' f' )
            ->select( "fc.descripcion || ' | ' || fsc.descripcion descripcion" , 'f.saldo - f.retencion saldo_disponible' , 'f.id' , '\'AG\' tipo' )
            ->leftJoin( TB_FONDO_CATEGORIA_SUB.' fsc' , 'f.subcategoria_id' , '=' , 'fsc.id' )
            ->leftJoin( TB_FONDO_CATEGORIA.' fc' , 'fsc.id_fondo_categoria' , '=' , 'fc.id' )
            ->where( 'fsc.tipo' , FONDO_SUBCATEGORIA_INSTITUCION )->get();
        }
        else
            return array();
    }

    protected function getSolProducts( $idSolProduct )
    {
        return SolicitudProduct::whereIn( 'id' , $idSolProduct )->lists( 'id_producto' );
    }

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

    protected function setMontoAsignadoAttribute( $value )
    {
        $this->attributes[ 'monto_asignado' ] = round( $value , 2 , PHP_ROUND_HALF_DOWN );
    }
}