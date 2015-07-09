<?php

namespace Dmkt;
use \Eloquent;
use \Auth;
use \DB;
use \Maintenance\Fondos;
use \Maintenance\FondosSupervisor;

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
            return DB::table('Fondos f')->select( "m.descripcion || ' | ' || fc.descripcion || ' | ' || fsc.descripcion descripcion" , 'f.saldo saldo' , 'fsc.id id' , 'm.id marca_id')
            ->leftJoin( 'fondos_subcategorias fsc' , 'f.fondos_subcategoria_id' , '=' , 'fsc.id' )
            ->leftJoin( 'fondos_categorias fc' , 'fsc.fondos_categorias_id' , '=' , 'fc.id' )
            ->leftJoin( 'outdvp.marcas m' , 'f.marca_id' , '=' , 'm.id' )
            ->where( function( $query ) use( $user )
            {
                if ( $user->type == GER_PROD )
                    $query->where( 'fsc.tipo' , FONDO_SUBCATEGORIA_GERPROD );
                elseif( $user->type == ASIS_GER )
                    $query->where( 'fsc.tipo' , 'I' );
                else
                    $query->where( 'fsc.tipo' , 'NNN' );
            })->orderBy( 'm.descripcion' , 'asc' )->get();
        else
        {
            if ( $solicitud->createdBy->type == REP_MED )
                $user = \User::find( $solicitud->createdBy->rm->rmSup->iduser );
            elseif ( $solicitud->createdBy->type == SUP )
                $user = \User::find( $solicitud->created_by );
            else
                $user = Auth::user();
        
            return DB::table('fondos_supervisor fs')
            ->select( "m.descripcion || ' | ' || fc.descripcion || ' | ' || fsc.descripcion descripcion" , 'fs.saldo saldo' , 'fsc.id id' , 'fs.marca_id marca_id' )
            ->leftJoin( 'fondos_subcategorias fsc' , 'fsc.id' , '=' , 'fs.subcategoria_id' )
            ->leftJoin( 'fondos_categorias fc' , 'fc.id' , '=' , 'fsc.fondos_categorias_id' )
            ->leftJoin( 'outdvp.marcas m' , 'fs.marca_id' , '=' , 'm.id' )
            ->where( 'fsc.tipo' , 'S' )->where('fs.supervisor_id' , $user->id )->orderBy( 'm.descripcion' , 'asc' )->get();
        }
    }

    protected function getSolProducts( $idSolProduct )
    {
        return SolicitudProduct::whereIn( 'id' , $idSolProduct )->lists( 'id_producto' );
    }

    public function thisSubFondo()
    {
        $user = \User::find( $this->id_fondo_user );
        \Log::error( $user->toJson() );
        if ( $user->type != SUP )
            $rpta = Fondos::where( 'marca_id' , $this->id_fondo_producto )->where( 'fondos_subcategoria_id' , $this->id_fondo )->first();
        else
            $rpta = FondosSupervisor::where( 'marca_id' , $this->id_fondo_producto )->where( 'subcategoria_id' , $this->id_fondo )
            ->where( 'supervisor_id' , $this->id_fondo_user )->first();
        \Log::error( DB::getQueryLog() );
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
        return $this->hasOne( 'Maintenance\FondosSubCategorias' , 'id' , 'id_fondo' );
    }

    public function user()
    {
        return $this->hasOne( 'User' , 'id' , 'id_fondo_user');
    }
}