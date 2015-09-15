<?php

namespace Fondo;

use \Eloquent;
use \DB;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class FondoInstitucional extends Eloquent
{

	use SoftDeletingTrait;

	protected $table      = TB_FONDO_INSTITUCION;
	protected $primaryKey = 'id';
	protected $fillable   = array('id','descripcion');

	protected function getSaldoDisponibleAttribute()
	{
		return $this->saldo - $this->retencion;
	}

	public function subCategoria()
	{
		return $this->hasOne( 'Fondo\FondoSubCategoria' , 'id' , 'subcategoria_id' );
	}

	public static function getSubFondo()
	{
		return DB::table( TB_FONDO_INSTITUCION.' f' )
            ->select( "fc.descripcion || ' | ' || fsc.descripcion descripcion" , 'f.saldo - f.retencion saldo_disponible' , 'f.id' , '\'AG\' tipo' )
            ->leftJoin( TB_FONDO_CATEGORIA_SUB.' fsc' , 'f.subcategoria_id' , '=' , 'fsc.id' )
            ->leftJoin( TB_FONDO_CATEGORIA.' fc' , 'fsc.id_fondo_categoria' , '=' , 'fc.id' )
            ->where( 'fsc.tipo' , FONDO_SUBCATEGORIA_INSTITUCION )->get();
    }

    protected static function order()
	{
		return FondoInstitucional::orderBy( 'updated_at' , 'desc' )->get();
	}

	protected static function orderWithTrashed()
	{
		return FondoInstitucional::orderBy( 'updated_at' , 'DESC' )->withTrashed()->get();
	}

	protected function setSaldoAttribute( $value )
	{
		$this->attributes[ 'saldo' ] = round( $value , 2 , PHP_ROUND_HALF_DOWN );
	}

	protected function setRetencionAttribute( $value )
	{
		$this->attributes[ 'retencion' ] = round( $value , 2 , PHP_ROUND_HALF_DOWN );
	}

	protected function getFullNameAttribute()
	{
		return $this->SubCategoria->descripcion;
	}

	protected function getMiddleNameAttribute()
	{
		return $this->SubCategoria->descripcion;
	}

}