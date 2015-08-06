<?php

namespace Fondo;

use \Eloquent;

class FondoSubCategoria extends Eloquent
{
	protected $table      = 'FONDO_SUBCATEGORIA';
	protected $primaryKey = 'id';
	protected $fillable   = array('id','descripcion', 'fondos_categorias_id');

	/*public function fondosCategorias()
	{
		return $this->belongsTo('Maintenance\FondosCategorias','fondos_categorias_id');
	}*/

	/*public function fondos()
	{
		return $this->hasMany('Maintenance\fondos');
	}*/

	public function accountFondo()
	{
		return $this->hasOne( 'Fondo\Fondo' , 'id' , 'id_fondo' );
	}
	
}