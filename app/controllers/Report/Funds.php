<?php

namespace Report;

use \BaseController;
use \View;
use \Input;
use \Fondo\FondoSupervisor;
use \Fondo\FondoSubCategoria;

class Funds extends BaseController
{

	public function show( $type )
	{
		$typeCode 	   = $this->getTypeCode( $type );
		$fondoCategory = FondoSubCategoria::getRolFunds( $typeCode );
		return View::make( 'Report.view' , [ 'type' => $type , 'funds' => $fondoCategory ] ); 
	}

	public function source()
	{
		$inputs = Input::all();
		return $this->getData( $inputs[ 'type' ] );
	}

	private function getTypeCode( $type )
	{
		if( $type === 'Fondo_Supervisor' )
		{
			return SUP;
		}

	}

	private function getData( $type )
	{
		if( $type === 'Fondo_Supervisor' )
		{
			$data = FondoSupervisor::getSupFund();
			$columns =
                [ 
                        [ 'data' => 'subcategoria.descripcion' ],
                        [ 'data' => 'marca.descripcion' ],
                        [ 'data' => 'saldo' , 'className' => 'sum-saldo' ],
                        [ 'data' => 'retencion' , 'className' => 'sum-retencion' ],
                        [ 'data' => 'saldo_disponible' , 'className' => 'sum-saldo-disponible' ]
                        
                ];
			$rpta = $this->setRpta( $data );
			$rpta[ 'columns' ] = $columns;
			$rpta[ 'message' ] = 'registros';
			return $rpta;
		}

	}

}