<?php

namespace Fondo;

use \Eloquent;

class FondoMktPeriodHistory extends Eloquent
{

	protected $table      = 'FONDO_MKT_PERIODO_HISTORIA';
	protected $primaryKey = 'id';

	protected static function getFondoMktPeriod( $period , $subCategoryId )
	{
		return FondoMktPeriodHistory::where( 'periodo' , $period )->where( 'subcategoria_id' , $subCategoryId )->first();
	}

	public function nextId()
	{
		$lastId = FondoMktPeriodHistory::orderBy( 'id' , 'DESC' )->first();
		if ( is_null( $lastId ) )
			return 1;
		else
			return $lastId->id + 1;
	}
	
}