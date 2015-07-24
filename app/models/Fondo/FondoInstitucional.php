<?php

namespace Fondo;

use \Eloquent;
use \DB;

class FondoInstitucional extends Eloquent
{

	protected $table      = 'FONDO_INSTITUCION';
	protected $primaryKey = 'id';
	protected $fillable   = array('id','descripcion');

	public function subCategoria()
	{
		return $this->hasOne( 'Fondo\FondoSubCategoria' , 'id' , 'subcategoria_id' );
	}

	public static function getSubFondo()
	{
		return DB::table( 'Fondo_Institucion f' )
            ->select( "fc.descripcion || ' | ' || fsc.descripcion descripcion" , 'f.saldo saldo' , 'f.id id' , '\'AG\' tipo' )
            ->leftJoin( 'fondo_subcategoria fsc' , 'f.subcategoria_id' , '=' , 'fsc.id' )
            ->leftJoin( 'fondo_categoria fc' , 'fsc.id_fondo_categoria' , '=' , 'fc.id' )
            ->where( 'fsc.tipo' , FONDO_SUBCATEGORIA_INSTITUCION )->get();
    }
}