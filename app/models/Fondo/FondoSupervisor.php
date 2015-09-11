<?php

namespace Fondo;

use \Eloquent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class FondoSupervisor extends Eloquent
{

	use SoftDeletingTrait;

	protected $table      = TB_FONDO_SUPERVISOR;
	protected $primaryKey = 'id';

	protected function getSaldoDisponibleAttribute()
	{
		return $this->saldo - $this->retencion;
	}

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
        return $this->hasOne( 'Users\Personal', 'user_id' , 'supervisor_id' );//No tiene informacion en el campo tipo->where( 'tipo' , '=' , 'S' );
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

	protected function setRetencionAttribute( $value )
	{
		$this->attributes[ 'retencion' ] = round( $value , 2 , PHP_ROUND_HALF_DOWN );
	}

	protected function getFullNameAttribute()
	{
		return $this->SubCategoria->descripcion . ' | ' . $this->marca->descripcion . ' | '  . $this->sup->full_name;
	}

	protected function getMiddleNameAttribute()
	{
		return $this->marca->descripcion . ' | '  . $this->sup->full_name;
	}

}