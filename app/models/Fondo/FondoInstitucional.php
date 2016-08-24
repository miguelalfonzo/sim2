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
		return round( $this->saldo - $this->retencion , 2 , PHP_ROUND_HALF_DOWN );
	}

	public function subCategoria()
	{
		return $this->hasOne( 'Fondo\FondoSubCategoria' , 'id' , 'subcategoria_id' );
	}

	public static function getSubFondo()
	{
		return  FondoInstitucional::whereHas( 'subcategoria' , function( $query )
	            {
	            	$query->where( 'trim( tipo )' , 'I' );
	            })->get();
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

	public function getDetailNameAttribute()
    {
    	$subCategory = $this->subCategoria;
        return $subCategory->categoria->descripcion . ' | ' . $subCategory->descripcion;
    }

}