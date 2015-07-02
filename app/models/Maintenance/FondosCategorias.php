<?php

namespace Maintenance;

use \Eloquent;

class FondosCategorias extends Eloquent
{
	protected $table      = 'FONDOS_CATEGORIAS';
	protected $primaryKey = 'id';
	public $incrementing  = false;
	protected $fillable   = array('id','descripcion');
}