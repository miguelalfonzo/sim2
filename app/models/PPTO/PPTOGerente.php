<?php

namespace PPTO;

use \Eloquent;
use \DB;

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

	private function maxVersion( $subCategory , $year )
	{
        $register = $this->select( 'max( version ) max_version' )
    		->where( 'anio' , $year )
            ->where( 'subcategoria_id' , $subCategory )
            ->first();
        
        if( is_null( $register ) )
        {
            return 0;
        }
        else
        {
            return $register->max_version;
        }
    }

	public function sumCategoryAmount( $subCategory , $year )
	{
		$maxVersion = $this->maxVersion( $subCategory , $year );
		if( $maxVersion == 0 )
		{
			return 0;
		}
		else
		{
			return $this->select( 'monto' )
				->where( 'subcategoria_id' , $subCategory )
				->where( 'anio' , $year )
				->where( 'version' , $maxVersion )
				->sum( 'monto' );
		}
	}

}