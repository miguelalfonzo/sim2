<?php

namespace Fondo;

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use \Eloquent;
use \Auth;
use \DB;

class FondoSupervisor extends Eloquent
{

	use SoftDeletingTrait;

	protected $table      = TB_FONDO_SUPERVISOR;
	protected $primaryKey = 'id';

	public function nextId()
	{
		$register = FondoSupervisor::orderBy( 'id' , 'DESC' )->first();
		if( is_null( $register ) )
		{
			return 1;
		}
		else
		{
			return $register->id + 1;
		}
	}

	protected function getSaldoDisponibleAttribute()
	{
		return round( $this->saldo - $this->retencion , 2 , PHP_ROUND_HALF_DOWN );
	}

	public function subCategoria()
	{
		return $this->hasOne( 'Fondo\FondoSubCategoria' , 'id' , 'subcategoria_id' );
	}

	public function marca()
	{
		return $this->belongsTo( 'Dmkt\Marca', 'marca_id' );
	}

	protected function sup()
	{
        return $this->hasOne( 'Users\Personal', 'user_id' , 'supervisor_id' );//No tiene informacion en el campo tipo->where( 'tipo' , '=' , 'S' );
    }

	protected static function order()
	{
		return FondoSupervisor::orderBy( 'updated_at' , 'desc' )->get();
	}

	protected static function orderWithTrashed()
	{
		return FondoSupervisor::orderBy( 'updated_at' , 'desc' )->withTrashed()->get();
	}	

	protected function setSaldoAttribute( $value )
	{
		$this->attributes[ 'saldo' ] = round( $value , 2 , PHP_ROUND_HALF_DOWN );
	}

	protected function setRetencionAttribute( $value )
	{
		$this->attributes[ 'retencion' ] = round( $value , 2 , PHP_ROUND_HALF_DOWN );
	}

	protected function getFullNameAttribute()
	{
		return $this->SubCategoria->descripcion . ' | ' . $this->marca->descripcion . ' | '  . $this->sup->full_name;
	}

	protected function getMiddleNameAttribute()
	{
		return $this->marca->descripcion . ' | '  . $this->sup->full_name;
	}

	protected function getApprovalProductNameAttribute()
	{
		return $this->subCategoria->descripcion . ' | ' . $this->marca->descripcion . ' S/.' . $this->saldo_disponible ;
	}

	public function getDetailNameAttribute()
    {
    	$subCategory = $this->subCategoria;
        return $this->marca->descripcion . ' | ' . $subCategory->categoria->descripcion . ' | ' . $subCategory->descripcion;
    }

	protected static function totalAmount( $subcategory , $supervisorId )
	{
		$model  =	FondoSupervisor::select( 'sum( saldo ) saldo , sum( retencion ) retencion , sum( saldo - retencion ) saldo_disponible , subcategoria_id' )
				  		->where( 'subcategoria_id' , $subcategory )->where( 'supervisor_id' , $supervisorId )
						->groupBy( 'subcategoria_id' )
						->first();
		return $model;
	}

	protected static function getSupFund( $category )
	{
		$supFunds = FondoSupervisor::select( 'subcategoria_id , marca_id , round( saldo , 2 ) saldo , retencion , ( saldo - retencion ) saldo_disponible' )
				   ->where( 'supervisor_id' , Auth::user()->id )->orderBy( 'subcategoria_id' )->with( 'subcategoria' , 'marca' );

		if( $category != 0 )
		{
			$supFunds = $supFunds->where( 'subcategoria_id' , $category );
		}
		
		return $supFunds->get();
	}

	protected static function getReportSupFund( $category )
	{
		$supFunds = FondoSupervisor::where( 'supervisor_id' , Auth::user()->id )->orderBy( 'subcategoria_id' );
		
		if( $category != 0 )
		{
			$supFunds = $supFunds->where( 'subcategoria_id' , $category );
		}
		
		return $supFunds->get();
	}

	protected function getUnique( $year , $category , $userId , $familyId )
	{
		return FondoSupervisor::where( 'anio' , $year )
			->where( 'subcategoria_id' , $category )
			->where( 'supervisor_id' , $userId )
			->where( 'marca_id' , $familyId )
			->first();
	}

	protected function updateFundAmount( $fundId , $diffAmount )
    {
        if( ! is_numeric( $diffAmount ) )
        {
            return -1;
        }
        return FondoSupervisor::where( 'id' , $fundId )
            ->update( [ 'saldo' => DB::raw( 'saldo + ' . $diffAmount ) ] ); 
    }

}