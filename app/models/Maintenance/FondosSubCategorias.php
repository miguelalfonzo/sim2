<?php

namespace Maintenance;

use \Eloquent;

class FondosSubCategorias extends Eloquent
{
	protected $table      = 'FONDOS_SUBCATEGORIAS';
	protected $primaryKey = 'id';
	public $incrementing  = false;
	protected $fillable   = array('id','descripcion', 'fondos_categorias_id');
}