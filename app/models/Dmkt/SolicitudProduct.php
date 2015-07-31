<?php

namespace Dmkt;
use \Eloquent;
use \Auth;
use \DB;
use \Fondo\FondoGerProd;
use \Fondo\FondoSupervisor;

class SolicitudProduct extends Eloquent
{
    protected $table = 'SOLICITUD_PRODUCTO';
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
            $userid = $solicitud->asigned_to->rm->rmSup->iduser;
            return DB::table('Fondo_Supervisor fs')
            ->select( "m.descripcion || ' | ' || fc.descripcion || ' | ' || fsc.descripcion descripcion" , 'fs.saldo saldo' , 'fs.id id' , 'fs.marca_id marca_id' , '\'S\' tipo' )
            ->leftJoin( 'fondo_subcategoria fsc' , 'fsc.id' , '=' , 'fs.subcategoria_id' )
            ->leftJoin( 'fondo_categoria fc' , 'fc.id' , '=' , 'fsc.id_fondo_categoria' )
            ->leftJoin( 'outdvp.marcas m' , 'fs.marca_id' , '=' , 'm.id' )
            ->where( 'fs.saldo' , '>' , 0 )->where( 'fsc.tipo' , FONDO_SUBCATEGORIA_SUPERVISOR )->where('fs.supervisor_id' , $userid )->orderBy( 'm.descripcion' , 'asc' )->get();
        }
        else if( $userType == GER_PROD )
        {
            return DB::table( 'Fondo_Gerente_Producto f' )
            ->select( "m.descripcion || ' | ' || fc.descripcion || ' | ' || fsc.descripcion descripcion" , 'f.saldo saldo' , 'f.id id' , 'f.marca_id marca_id' , '\'P\' tipo' )
            ->leftJoin( 'fondo_subcategoria fsc' , 'f.subcategoria_id' , '=' , 'fsc.id' )
            ->leftJoin( 'fondo_categoria fc' , 'fsc.id_fondo_categoria' , '=' , 'fc.id' )
            ->leftJoin( 'outdvp.marcas m' , 'f.marca_id' , '=' , 'm.id' )
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
            return DB::table( 'Fondo_Institucion f' )
            ->select( "fc.descripcion || ' | ' || fsc.descripcion descripcion" , 'f.saldo saldo' , 'f.id id' , '\'AG\' tipo' )
            ->leftJoin( 'fondo_subcategoria fsc' , 'f.subcategoria_id' , '=' , 'fsc.id' )
            ->leftJoin( 'fondo_categoria fc' , 'fsc.id_fondo_categoria' , '=' , 'fc.id' )
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
}