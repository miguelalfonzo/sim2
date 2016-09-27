<?php

namespace PPTO;

use \Eloquent;

class PPTOSupervisor extends Eloquent
{

	protected $table      = 'PPTO_SUPERVISOR';
	protected $primaryKey = 'id';

	public static function nextVersion( $year , $subCategory )
	{
		$register = PPTOSupervisor::select( 'max( version ) max_version' )
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
		return PPTOSupervisor::select( [ 'id' , 'subcategoria_id' , 'marca_id' , 'supervisor_id' , 'monto' ] )
			->with( [ 'subCategory' , 'family' , 'personal'] )
			->where( 'anio' , $year )
			->where( 'subcategoria_id' , $subCategory )
			->where( 'version' , $version - 1 )
			->orderBy( 'supervisor_id' , 'ASC' )
			->orderBy( 'marca_id' , 'ASC' )->get();
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