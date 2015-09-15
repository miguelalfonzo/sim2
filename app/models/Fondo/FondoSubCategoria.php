<?php

namespace Fondo;

use \Eloquent;

class FondoSubCategoria extends Eloquent
{
	protected $table      = TB_FONDO_CATEGORIA_SUB;
	protected $primaryKey = 'id';
	protected $fillable   = array('id','descripcion', 'fondos_categorias_id');

	protected static function order()
	{
		return FondoSubCategoria::orderBy( 'descripcion' , 'ASC' )->get();
	}

	protected function fondoMktType()
	{
		return $this->hasOne( 'Fondo\FondoMktType' , 'codigo' , 'tipo' );
	}
	
	protected function fromSupFund()
	{
		return $this->hasMany( 'Fondo\FondoSupervisor' , 'subcategoria_id' );
	}

	protected function fromGerProdFund()
	{
		return $this->hasMany( 'Fondo\FondoGerProd' , 'subcategoria_id' );
	}

	protected function fromInstitutionFund()
	{
		return $this->hasMany( 'Fondo\FondoInstitucional' , 'subcategoria_id' );
	}

	protected function fund()
	{
		if ( trim( $this->tipo ) == SUP )
		{
			return $this->hasMany( 'Fondo\FondoSupervisor' , 'subcategoria_id' );
		}
		elseif ( in_array( trim( $this->tipo ) , array( GER_PROD , GER_PROM ) ) )
		{
			return $this->hasMany( 'Fondo\FondoGerProd' , 'subcategoria_id' );
		}
		elseif ( trim( $this->tipo ) == 'I' )
		{
			return $this->hasMany( 'Fondo\FondoInstitucional' , 'subcategoria_id' );
		}
	}

}