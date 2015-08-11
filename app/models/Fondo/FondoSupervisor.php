<?php

namespace Fondo;

use \Eloquent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class FondoSupervisor extends Eloquent
{

	use SoftDeletingTrait;

	protected $table      = TB_FONDO_SUPERVISOR;
	protected $primaryKey = 'id';

	public function subCategoria()
	{
		return $this->hasOne( 'Fondo\FondoSubCategoria' , 'id' , 'subcategoria_id' );
	}

	public function marca()
	{
		return $this->belongsTo( 'Dmkt\Marca', 'marca_id' );
	}

	protected function sup()
	{
		return $this->hasOne( 'Users\Sup' , 'iduser' , 'supervisor_id' );
	}

	protected static function order()
	{
		return FondoSupervisor::orderBy( 'updated_at' , 'desc' )->get();
	}

	protected static function orderWithTrashed()
	{
		return FondoSupervisor::orderBy( 'updated_at' , 'desc' )->withTrashed()->get();
	}	

	protected function setSaldoAttribute( $value )
	{
		$this->attributes[ 'saldo' ] = round( $value , 2 , PHP_ROUND_HALF_DOWN );
	}

	protected function setSaldoNetoAttribute( $value )
	{
		$this->attributes[ 'saldo_neto' ] = round( $value , 2 , PHP_ROUND_HALF_DOWN );
	}

}