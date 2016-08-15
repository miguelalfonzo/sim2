<?php

namespace Report;

use \BaseController;
use \View;
use \Custom\DataList;
use \Input;
use \Carbon\Carbon;
use \Excel;
use \Response;

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
            $dates  = [ 'start' => $inputs[ 'fecha_inicio' ] , 'end' => $inputs[ 'fecha_final' ] ];
            $data = DataList::getAmountReport( $inputs[ 'colaborador' ] , $dates , $inputs[ 'num_cuenta' ] , $inputs[ 'solicitud_id' ] );
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

	public function export()
	{
        try
        {
    		$inputs = Input::all();
            $dates  = [ 'start' => $inputs[ 'fecha_inicio' ] , 'end' => $inputs[ 'fecha_final' ] ];
            $data = DataList::getAmountReport( $inputs[ 'colaborador' ] , $dates , $inputs[ 'num_cuenta' ] , $inputs[ 'solicitud_id' ] );
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

            $now = Carbon::now();
            $title = 'Rep-' . $now->format( 'YmdHi' );
            $directoryPath = 'files/reporte/contabilidad/' . $inputs[ 'type' ];

    		Excel::create( $title , function( $excel ) use ( $data , $columns )
            {  
                $excel->sheet( 'Data' , function( $sheet ) use ( $data , $columns )
                {
                    $sheet->freezeFirstRow();
                    $sheet->loadView( 'Report.account.table' , [ 'data' => $data , 'columns' => $columns ] );
                });  
            })->store( 'xls' , public_path( $directoryPath ) );

            $rpta = $this->setRpta();
            $rpta[ 'title' ] = $title;
            return $rpta;
	    }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    public function download( $title )
    {
        try
        {
            $directoryPath = 'files/reporte/contabilidad/monto';
            $filePath = $directoryPath . '/' . $title . '.xls';
            return Response::download( $filePath );
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }
}