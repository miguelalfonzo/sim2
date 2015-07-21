<?php

namespace Fondo;

use \Eloquent;

class FondoGerProd extends Eloquent
{
	protected $table      = 'FONDO_GERENTE_PRODUCTO';
	protected $primaryKey = 'id';
	protected $fillable   = array('id','fondos_subcategoria_id','marca_id');

	public function fondosSubCategorias()
	{
		return $this->belongsTo('Maintenance\FondosSubCategorias', 'fondos_subcategoria_id' );
	}

	public function marca()
	{
		return $this->belongsTo('Dmkt\Marca', 'marca_id' );
	}

	public function subCategoria()
	{
		return $this->hasOne( 'Fondo\FondoSubCategoria' , 'id' , 'subcategoria_id' );
	}
}