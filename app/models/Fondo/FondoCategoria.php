<?php

namespace Fondo;

use \Eloquent;

class FondoCategoria extends Eloquent
{
	protected $table      = 'FONDO_CATEGORIA';
	protected $primaryKey = 'id';
	protected $fillable   = array('id','descripcion');

	public function fondosSubCategorias()
	{
		return $this->hasMany('Maintenance\fondosSubCategorias');
	}
}