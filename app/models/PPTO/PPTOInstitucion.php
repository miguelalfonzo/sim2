<?php

namespace PPTO;

use \Eloquent;

class PPTOInstitucion extends Eloquent
{

	protected $table      = 'PPTO_INSTITUCION';
	protected $primaryKey = 'id';

	private function nextVersion( $year )
	{
		$register = PPTOInstitucion::select( 'max( version ) max_version' )->where( 'anio' , $year )->first();
		if( is_null( $register ) )
		{
			return 1;
		}
		else
		{
			return $register->max_version + 1;
		}
	}

	public function getPPTO( $year )
	{
		$version = $this->nextVersion( $year );
		return PPTOInstitucion::select( [ 'id' , 'monto' , 'subcategoria_id' ] )
			->with( [ 'subCategory' ] )
			->where( 'anio' , $year )
			->where( 'version' , $version - 1 )
			->get();
	}

	public function subCategory()
	{
		return $this->belongsTo( 'Fondo\FondoSubCategoria' , 'subcategoria_id' );
	}


}