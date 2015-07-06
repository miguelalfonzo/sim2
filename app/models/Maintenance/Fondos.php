<?php

namespace Maintenance;

use \Eloquent;

class Fondos extends Eloquent
{
	protected $table      = 'FONDOS';
	protected $primaryKey = 'id';
	public $incrementing  = false;
	protected $fillable   = array('id','fondos_subcategoria_id','marca_id');

	public function fondosSubCategorias()
	{
		return $this->belongsTo('Maintenance\FondosSubCategorias', 'fondos_subcategoria_id' );
	}

	public function marca()
	{
		return $this->belongsTo('Dmkt\Marca', 'marca_id' );
	}
}