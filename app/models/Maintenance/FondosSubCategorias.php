<?php

namespace Maintenance;

use \Eloquent;

class FondosSubCategorias extends Eloquent
{
	protected $table      = 'FONDOS_SUBCATEGORIAS';
	protected $primaryKey = 'id';
	public $incrementing  = false;
	protected $fillable   = array('id','descripcion', 'fondos_categorias_id');

	public function fondosCategorias(){
		return $this->belongsTo('Maintenance\FondosCategorias','fondos_categorias_id');
	}
	public function fondos()
	{
		return $this->hasMany('Maintenance\fondos');
	}

}