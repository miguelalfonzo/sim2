<?php

namespace Dmkt;
use \Eloquent;
use \Auth;
use \DB;

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

    public function getSubFondo()
    {
        /*if ( Auth::user()->type == SUP )
            return $this->hasMany( 'Maintenance\FondosSupervisor' , 'marca_id' , 'id_producto' )
            ->leftJoin( 'fondos_subcategorias fsc' , 'fsc.id' , '=' , 'fs.subcategoria_id' );
        else
            return $this->hasMany( 'Maintenance\Fondos' , 'marca_id' , 'id_producto');
*/
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
            })->get();
        else
            return DB::table('fondos_supervisor fs')
            ->select( "m.descripcion || ' | ' || fc.descripcion || ' | ' || fsc.descripcion descripcion" , 'fs.saldo saldo' , 'fsc.id id' , 'fs.marca_id marca_id' )
            ->leftJoin( 'fondos_subcategorias fsc' , 'fsc.id' , '=' , 'fs.subcategoria_id' )
            ->leftJoin( 'fondos_categorias fc' , 'fc.id' , '=' , 'fsc.fondos_categorias_id' )
            ->leftJoin( 'outdvp.marcas m' , 'fs.marca_id' , '=' , 'm.id' )
            ->where( 'fsc.tipo' , 'S' )->where('fs.supervisor_id' , $user->id )->get();
    }

    protected function getSolProducts( $idSolProduct )
    {
        return SolicitudProduct::whereIn( 'id' , $idSolProduct )->lists( 'id_producto' );
    }

    public function marca()
    {
        return $this->hasOne( 'Dmkt\Marca' , 'id' , 'id_producto' );
    }

    public function subCatFondo()
    {
        return $this->hasOne( 'Maintenance\FondosSubCategorias' , 'id' , 'id_fondo' );
    }
}