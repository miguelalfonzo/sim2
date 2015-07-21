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

    public function getSubFondo( $solicitud )
    {
        $user = Auth::user();
        if ( $user->type != SUP )
            return DB::table( 'Fondo_Gerente_Producto f' )->select( "m.descripcion || ' | ' || fc.descripcion || ' | ' || fsc.descripcion descripcion" , 'f.saldo saldo' , 'fsc.id id' , 'm.id marca_id')
            ->leftJoin( 'fondo_subcategoria fsc' , 'f.subcategoria_id' , '=' , 'fsc.id' )
            ->leftJoin( 'fondo_categoria fc' , 'fsc.id_fondo_categoria' , '=' , 'fc.id' )
            ->leftJoin( 'outdvp.marcas m' , 'f.marca_id' , '=' , 'm.id' )
            ->where( function( $query ) use( $user )
            {
                if ( $user->type == GER_PROD )
                    $query->where( 'fsc.tipo' , FONDO_SUBCATEGORIA_GERPROD );
                elseif( $user->type == ASIS_GER )
                    $query->where( 'fsc.tipo' , 'I' );
                else
                    $query->where( 'fsc.tipo' , 'NNN' );
            })->where( 'f.saldo' , '>' , 0 )->orderBy( 'm.descripcion' , 'asc' )->get();
        else
        {
            if ( $solicitud->createdBy->type == REP_MED )
                $user = \User::find( $solicitud->createdBy->rm->rmSup->iduser );
            elseif ( $solicitud->createdBy->type == SUP )
                $user = \User::find( $solicitud->created_by );
            else
                $user = Auth::user();
        
            return DB::table('fondo_supervisor fs')
            ->select( "m.descripcion || ' | ' || fc.descripcion || ' | ' || fsc.descripcion descripcion" , 'fs.saldo saldo' , 'fsc.id id' , 'fs.marca_id marca_id' )
            ->leftJoin( 'fondo_subcategoria fsc' , 'fsc.id' , '=' , 'fs.subcategoria_id' )
            ->leftJoin( 'fondo_categoria fc' , 'fc.id' , '=' , 'fsc.id_fondo_categoria' )
            ->leftJoin( 'outdvp.marcas m' , 'fs.marca_id' , '=' , 'm.id' )
            ->where( 'fs.saldo' , '>' , 0 )->where( 'fsc.tipo' , 'S' )->where('fs.supervisor_id' , $user->id )->orderBy( 'm.descripcion' , 'asc' )->get();
        }
    }

    protected function getSolProducts( $idSolProduct )
    {
        return SolicitudProduct::whereIn( 'id' , $idSolProduct )->lists( 'id_producto' );
    }

    public function thisSubFondo()
    {
        if ( $this->id_tipo_fondo_marketing == GER_PROD )
            $rpta = FondoGerProd::find( $this->id_fondo_marketing );
        else
            $rpta = FondoSupervisor::find( $this->id_fondo_marketing );
        return $rpta;
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