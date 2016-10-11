<?php

namespace PPTO;

use \Eloquent;

class PPTOSupervisor extends Eloquent
{

	protected $table      = 'PPTO_SUPERVISOR';
	protected $primaryKey = 'id';

	public function getPPTO( $year , $subCategory , $version )
	{
		return $this->select( [ 'id' , 'subcategoria_id' , 'marca_id' , 'supervisor_id' , 'monto' , 'anio' , 'version' ] )
			->with( [ 'subCategory' , 'family' , 'personal'] )
			->where( 'anio' , $year )
			->where( 'subcategoria_id' , $subCategory )
			->where( 'version' , $version )
			->orderBy( 'supervisor_id' , 'ASC' )
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

	public function personal()
	{
		return $this->hasOne( 'Users\Personal' ,  'user_id' , 'supervisor_id' );
	}

}