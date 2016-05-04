<?php

namespace Report;

use \BaseController;
use \View;
use \Input;
use \Fondo\FondoSupervisor;

class Funds extends BaseController
{

	public function show( $type )
	{
		return View::make( 'Report.view' , [ 'type' => $type ] ); 
	}

	public function source()
	{
		$inputs = Input::all();
		return $this->getData( $inputs[ 'type' ] );
	}

	private function getData( $type )
	{
		if( $type === 'Fondo_Supervisor' )
		{
			$data = FondoSupervisor::getSupFund();
			$columns =
                [ 
                        [ 'title' => 'Id' , 'data' => 'id' ],
                        [ 'title' => 'Saldo' , 'data' => 'saldo' , 'className' => 'sum' ]
                ];
			$rpta = $this->setRpta( $data );
			$rpta[ 'columns' ] = $columns;
			$rpta[ 'message' ] = 'registros';
			return $rpta;
		}

	}

}