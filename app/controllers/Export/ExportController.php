<?php

namespace Export;

use \BaseController;
use \Input;
use \View;
use \Exception;
use \PDF;
use \Dmkt\Solicitud;
use \Excel;

class ExportController extends BaseController
{
	public function exportSolicitudToDepositPDF()
	{
		try
		{
			$data = array( 'solicituds' => Solicitud::getDepositSolicituds(); );
			$view = View::make( 'Dmkt.Cont.reportToDeposit' , $data )->render();
			return PDF::load( $view , 'A4' , 'landscape' )->show();
		}
		catch( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	public function exportSolicitudToDepositExcel()
	{
		try
		{
			$data = array( 'solicituds' => Solicitud::where( 'id_estado' , DEPOSITO_HABILITADO )->orderBy( 'id' , 'ASC' )->limit( 10 )->get() );
			Excel::create( 'Solicitudes a Depositar' , function( $excel ) use( $data )
			{
				$excel->sheet( 'solicitudes' , function( $sheet ) use( $data )
				{
					$sheet->loadView( 'Dmkt.Cont.reportToDepositExcel' , $data );
				});
			})->download( 'xls' );
		}
		catch( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}
}