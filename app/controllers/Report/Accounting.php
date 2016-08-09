<?php

namespace Report;

use \BaseController;
use \View;
use \Custom\DataList;
use \Input;

class Accounting extends BaseController
{

	public function show( $type )
	{
		$data = $this->getReportType( $type );
		return View::make( $data[ 'View' ] , $data ); 
	}

	private function getReportType( $type )
	{
		if( $type == 'monto' )
		{
			$data =
			[
				'View' => 'Report.account.view',
				'type' => $type
			];
		}
		return $data;
	}

	public function source()
	{
		try
        {
            $inputs = Input::all();
            $dates  = [ 'start' => '01/01/2016' , 'end' => '01/08/2016' ];
            //$data   = $this->searchUserSolicituds( $inputs[ 'estado' ] , $dates , null );
            $data = DataList::getAmountReport( 0 , $dates , 0 , 0 );
            if( isset( $data[ status ] ) && $data[ status ] == error )
            {
                return $data;
            }

            $columns =
                [
                    [ 'title' => '#' , 'data' => 'id' , 'className' => 'text-center' ],
                    [ 'title' => 'Colaborador' , 'data' => 'empl_nom' , 'className' => 'text-center' ],
                    [ 'title' => 'Cuenta' , 'data' => 'cuenta_num' , 'className' => 'text-center' ],
                    [ 'title' => 'Fecha' , 'data' => 'dep_fec' , 'className' => 'text-center' ],
                    [ 'title' => 'Monto Depositado' , 'data' => 'dep_mon' , 'className' => 'text-center' ],
                    [ 'title' => 'Monto Regularizado' , 'data' => 'reg_mon' , 'className' => 'text-center' ],
                    [ 'title' => 'Monto Devuelto' , 'data' => 'dev_mon' , 'className' => 'text-center' ],
                    [ 'title' => 'Estado de Cuenta' , 'data' => 'debe' , 'className' => 'text-center' ]
                ];
            $rpta = $this->setRpta( $data );
            $rpta[ 'columns' ] = $columns;
            return $rpta;
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
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
}