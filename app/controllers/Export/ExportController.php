<?php

namespace Export;

use \BaseController;
use \Input;
use \View;
use \Exception;
use \PDF;
use \Dmkt\Solicitud;

class ExportController extends BaseController
{
	public function exportSolicitudToDeposit()
	{
		try
		{
			$data = array( 'solicituds' => Solicitud::where( 'id_estado' , DEPOSITO_HABILITADO )->orderBy( 'id' , 'ASC' )->get() );
			$view = View::make( 'Dmkt.Cont.reportToDeposit' , $data )->render();
			return PDF::load( $view , 'A4' , 'landscape' )->show();
		}
		catch( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}
}