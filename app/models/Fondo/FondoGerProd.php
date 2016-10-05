<?php

namespace Fondo;

use \Eloquent;
use \Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class FondoGerProd extends Eloquent
{

	use SoftDeletingTrait;

	protected $table      = TB_FONDO_GERENTE_PRODUCTO;
	protected $primaryKey = 'id';
	protected $fillable   = array('id','fondos_subcategoria_id','marca_id');

	protected function getSaldoDisponibleAttribute()
	{
		return round( $this->saldo - $this->retencion , 2 , PHP_ROUND_HALF_DOWN );
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
		$now = Carbon::now();
		return FondoGerProd::select( [ 'id' , 'subcategoria_id' , 'marca_id' , 'saldo' , 'retencion' ] )
			->where( 'subcategoria_id' , '<>' , 31 )
			->where( 'anio' , '=' , $now->format( 'Y' ) )
			->orderBy( 'updated_at' , 'DESC' )->withTrashed()->get();
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

	public function getDetailNameAttribute()
    {
    	$subCategory = $this->subCategoria;
        return $this->marca->descripcion . ' | ' . $subCategory->categoria->descripcion . ' | ' . $subCategory->descripcion;
    }

}