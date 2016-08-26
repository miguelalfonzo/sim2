<?php

namespace PPTO;

use \Eloquent;

class PPTOSupervisor extends Eloquent
{

	protected $table      = 'PPTO_SUPERVISOR';
	protected $primaryKey = 'id';

	protected function nextId()
	{
		$register = PPTOSupervisor::orderBy( 'id' , 'DESC' )->first();
		if( is_null( $register ) )
		{
			return 1;
		}
		else
		{
			return $register->id + 1;
		}
	}

	public static function nextVersion( $year )
	{
		$register = PPTOSupervisor::select( 'max( version ) max_version' )->where( 'anio' , $year )->first();
		if( is_null( $register ) )
		{
			return 1;
		}
		else
		{
			return $register->max_version + 1;
		}
	}

	protected static function getLast( $year , $category , $user_id , $cod129 , $version )
	{
		return PPTOSupervisor::where( 'anio' , $year )
			->where( 'subcategoria_id' , $category )
			->where( 'supervisor_id' , $user_id )
			->where( 'marca_id' , $cod129 )
			->where( 'version' , $version )
			->first();
	}

	protected static function getSameLast( $year , $category , $user_id , $cod129 , $version , $amount )
	{
		return PPTOSupervisor::where( 'anio' , $year )
			->where( 'subcategoria_id' , $category )
			->where( 'supervisor_id' , $user_id )
			->where( 'marca_id' , $cod129 )
			->where( 'version' , $version )
			->where( 'monto' , $amount )
			->first();
	}

	public function insertPPTO( $data )
	{
		$this->id              = $this->nextId();
		$this->version         = $data->version;
		$this->anio            = $data->year;
		$this->supervisor_id   = $data->user_id;
		$this->subcategoria_id = $data->sub_category_id;
		$this->marca_id        = $data->family_id;
		$this->monto           = $data->amount;
		$this->save();
	}

}