<?php

namespace Fondo;

use \Eloquent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class FondoSubCategoria extends Eloquent
{

	use SoftDeletingTrait;

	protected $table      = TB_FONDO_CATEGORIA_SUB;
	protected $primaryKey = 'id';
	protected $fillable   = array('id','descripcion', 'fondos_categorias_id');

	public function nextId()
	{
	    $nextId = $this->withTrashed()
	    	->select( 'id' )
	    	->orderBy( 'id' , 'desc' )
	    	->first();
        if ( is_null( $nextId ) )
            return 1;
        else
            return $nextId->id + 1;
    }

	protected static function order()
	{
		return FondoSubCategoria::orderBy( 'descripcion' , 'ASC' )->get();
	}

	public function categoria()
	{
		return $this->belongsTo( 'Fondo\FondoCategoria' , 'id_fondo_categoria' );
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

	public function fund()
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

	protected function getRolFunds( $typeCode )
	{
		return FondoSubCategoria::where( 'trim( tipo )' , $typeCode )->get();
	}

	protected static function orderWithTrashed()
	{
		return FondoSubCategoria::withTrashed()
			->where( 'trim( tipo )' , '<>' , 'O' )
			->orderBy( 'id_fondo_categoria' , 'ASC' )
			->orderBy( 'position' , 'ASC' )
			->get();
	}

}