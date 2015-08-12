<?php

namespace Fondo;

use \Eloquent;

class FondoSubCategoria extends Eloquent
{
	protected $table      = TB_FONDO_CATEGORIA_SUB;
	protected $primaryKey = 'id';
	protected $fillable   = array('id','descripcion', 'fondos_categorias_id');

	/*public function fondosCategorias()
	{
		return $this->belongsTo('Maintenance\FondosCategorias','fondos_categorias_id');
	}*/

	protected static function order()
	{
		return FondoSubCategoria::orderBy( 'descripcion' , 'ASC' )->get();
	}

	protected function fondoMktType()
	{
		return $this->hasOne( 'Fondo\FondoMktType' , 'codigo' , 'tipo' );
	}
	
}