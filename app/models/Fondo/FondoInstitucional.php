<?php

namespace Fondo;

use \Eloquent;

class FondoInstitucional extends Eloquent
{

	protected $table      = 'FONDO_INSTITUCIONAL';
	protected $primaryKey = 'id';
	protected $fillable   = array('id','descripcion');

	public function subCategoria()
	{
		return $this->hasOne( 'Fondo\FondoSubCategoria' , 'id' , 'subcategoria_id' );
	}
}