<?php

namespace PPTO;

use \Eloquent;

class PPTOGerente extends Eloquent
{

	protected $table      = 'PPTO_GERENTE';
	protected $primaryKey = 'id';

	private function nextVersion( $year , $subCategory )
	{
		$register = PPTOGerente::select( 'max( version ) max_version' )
			->where( 'anio' , $year )
			->where( 'subcategoria_id' , $subCategory )
			->first();
		if( is_null( $register ) )
		{
			return 1;
		}
		else
		{
			return $register->max_version + 1;
		}
	}

	public function getPPTO( $year , $subCategory )
	{
		$version = $this->nextVersion( $year , $subCategory );
		return PPTOGerente::select( [ 'id' , 'subcategoria_id' , 'marca_id' , 'monto' ] )
			->with( [ 'subCategory' , 'family' ] )
			->where( 'anio' , $year )
			->where( 'version' , $version - 1 )
			->get();
	}

	public function subCategory()
	{
		return $this->belongsTo( 'Fondo\FondoSubCategoria' , 'subcategoria_id' );
	}

	public function family()
	{
		return $this->belongsTo( 'Dmkt\Marca' , 'marca_id' );
	}


}