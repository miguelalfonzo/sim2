<?php

namespace Maintenance;

use \Eloquent;

class FondosSupervisor extends Eloquent
{

	protected $table      = 'FONDOS_SUPERVISOR fs';
	protected $primaryKey = 'id';
	

	public function subCategoria()
	{
		return $this->hasOne( 'Maintenance\FondosSubCategorias' , 'id' , 'subcategoria_id');
	}
}