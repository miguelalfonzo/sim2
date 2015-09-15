<?php

namespace Fondo;

use \Eloquent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class FondoGerProd extends Eloquent
{

	use SoftDeletingTrait;

	protected $table      = TB_FONDO_GERENTE_PRODUCTO;
	protected $primaryKey = 'id';
	protected $fillable   = array('id','fondos_subcategoria_id','marca_id');

	protected function getSaldoDisponibleAttribute()
	{
		return $this->saldo - $this->retencion;
	}

	public function fondosSubCategorias()
	{
		return $this->belongsTo('Maintenance\FondosSubCategorias', 'fondos_subcategoria_id' );
	}

	public function marca()
	{
		return $this->belongsTo('Dmkt\Marca', 'marca_id' );
	}

	public function subCategoria()
	{
		return $this->hasOne( 'Fondo\FondoSubCategoria' , 'id' , 'subcategoria_id' );
	}

	protected static function order()
	{
		return FondoGerProd::orderBy( 'updated_at' , 'desc' )->get();
	}

	protected static function orderWithTrashed()
	{
		return FondoGerProd::orderBy( 'updated_at' , 'DESC' )->withTrashed()->get();
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
		return $this->SubCategoria->descripcion . ' | ' . $this->marca->descripcion;
	}

	protected function getMiddleNameAttribute()
	{
		return $this->marca->descripcion;
	}

	protected function getApprovalProductNameAttribute()
	{
		return $this->subCategoria->descripcion . ' | ' . $this->marca->descripcion . ' S/.' . $this->saldo_disponible ;
	}

}