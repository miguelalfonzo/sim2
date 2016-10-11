<?php

namespace PPTO;

use \Eloquent;

class PPTOGerente extends Eloquent
{

	protected $table      = 'PPTO_GERENTE';
	protected $primaryKey = 'id';

	public function getPPTO( $year , $subCategory , $version )
	{
		return $this->select( [ 'id' , 'subcategoria_id' , 'marca_id' , 'monto' , 'anio' , 'version' ] )
			->with( [ 'subCategory' , 'family' ] )
			->where( 'anio' , $year )
			->where( 'subcategoria_id' , $subCategory )
			->where( 'version' , $version )
			->orderBy( 'marca_id' , 'ASC' )
			->get();
	}

	public function getVersions( $year , $subCategory )
	{
		return $this->distinct()
			->select( 'version' )
			->where( 'anio' , $year )
			->where( 'subcategoria_id' , $subCategory )
			->orderBy( 'version' , 'ASC' )
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