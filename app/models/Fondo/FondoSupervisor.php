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
}