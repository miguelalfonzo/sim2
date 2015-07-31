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
        return $this->hasOne( 'Parameter\Tablas' , 'codigo' , 'marca_id' )->where( 'tipo' , TIPO_FAMILIA );
    }

	public function subCategoria()
	{
		return $this->hasOne( 'Fondo\FondoSubCategoria' , 'id' , 'subcategoria_id' );
	}

	protected static function order()
	{
		return FondoGerProd::orderBy( 'updated_at' , 'desc' )->get();
	}
}