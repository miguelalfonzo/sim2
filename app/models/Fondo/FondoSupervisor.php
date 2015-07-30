<?php

namespace Fondo;

use \Eloquent;

class FondoSupervisor extends Eloquent
{

	protected $table      = 'FONDO_SUPERVISOR';
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
}