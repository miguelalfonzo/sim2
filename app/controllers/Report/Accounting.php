<?php

namespace Report;

use \BaseController;
use \View;

class Accounting extends BaseController
{

	public function show( $type )
	{
		return View::make( 'Report.account.view'  , [ 'type' => $type ] ); 
	}

	public function source()
	{
		$inputs = Input::all();
		return $this->getData( $inputs[ 'type' ] , $inputs[ 'category' ] );
	}

	public function export( $type , $category )
	{
		$data = $this->getData( $type , $category );
		Excel::create( 'Reporte '. $type , function( $excel ) use ( $data  )
        {  
            $excel->sheet( 'Data' , function( $sheet ) use ( $data )
            {
                $sheet->loadView( 'Report.table' , $data );
            });  
        })->export( 'xls' ); 
	}

	private function getTypeCode( $type )
	{
		if( $type === 'Fondo_Supervisor' )
		{
			return SUP;
		}
	}

	private function getData( $type , $category )
	{
		if( $type === 'Fondo_Supervisor' )
		{
			$data = FondoSupervisor::getSupFund( $category );
			$columns =
                [ 
                        [ 'data' => 'subcategoria.descripcion' , 'name' => 'Nombre' , 'relations' => [ 'subcategoria' , 'descripcion' ] ],
                        [ 'data' => 'marca.descripcion' , 'name' => 'Familia' , 'relations' => [ 'marca' , 'descripcion' ] ], 
                        [ 'data' => 'saldo' , 'className' => 'sum-saldo' , 'name' => 'Saldo S/.' ],
                        [ 'data' => 'retencion' , 'className' => 'sum-retencion' , 'name' => 'Retencion S/.' ],
                        [ 'data' => 'saldo_disponible' , 'className' => 'sum-saldo-disponible' , 'name' => 'Saldo Disponible S/.' ]
                        
                ];
			$rpta = $this->setRpta( $data );
			$rpta[ 'columns' ] = $columns;
			$rpta[ 'message' ] = 'registros';
			return $rpta;
		}
	}
}