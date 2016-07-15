<?php

namespace Export;

use \BaseController;
use \Input;
use \View;
use \Exception;
use \PDF;
use \Dmkt\Solicitud;
use \Excel;
use \Carbon\Carbon;
use \Session;
use \File;
use \Response;

class ExportController extends BaseController
{
	public function exportSolicitudToDepositPDF()
	{
		try
		{
			$data = array( 'solicituds' => Solicitud::getDepositSolicituds() );
			$view = View::make( 'Dmkt.Cont.SolicitudsToDeposit.pdf' , $data )->render();
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
			$solicituds = Solicitud::getDepositSolicituds();
			$data = array( 'solicituds' => $solicituds );
			Excel::create( 'Solicitudes a Depositar' , function( $excel ) use( $data )
			{
				$excel->sheet( 'solicitudes' , function( $sheet ) use( $data )
				{
					$sheet->loadView( 'Dmkt.Cont.SolicitudsToDeposit.excel' , $data );
				});
			})->download( 'xls' );
		}
		catch( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	public function revisionExport()
    {
        try
        {
            $now  = Carbon::now();
            $date = $now->toDateString();
            $title = 'Detalle del Procesamiento de Solicitudes-';
            $directoryPath  = 'files/revisiones';
            $filePath = $directoryPath . '/' . $title . $date . '.xls';
            
            $data = [];
            if( File::exists( public_path( $filePath ) ) )
            {
                $oldResponses = Excel::load( public_path( $filePath ) )->get();
                $data[ 'oldResponses' ] = $oldResponses;
            }

            if( Session::has( 'revisiones' ) )
            {
                $responses = Session::pull( 'revisiones' );
                $data[ 'responses' ] = $responses;
            }

            if( ! isset( $oldResponses ) && ! isset( $responses ) )
            {
                return $this->warningException( 'No se pudo exportar el excel con las observaciones de las solicitudes procesadas' , __FUNCTION__ , __LINE__ , __FILE__ );
            }
            
            Excel::create( $title . $date , function( $excel ) use( $data )
            {
                $excel->sheet( 'solicitudes' , function( $sheet ) use( $data )
                {
                    $sheet->freezeFirstRow();
                    $sheet->setStyle( 
                        array(
                            'font' => 
                                array(
                                    'bold' => true
                                )
                            )
                        );
                    $sheet->loadView( 'Dmkt.Cont.Excel.revision_detail' , $data );
                });
            })->store( 'xls' , public_path( $directoryPath ) );
            return Response::download( $filePath );
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    public function advanceEntryExport()
    {
        try
        {
            $now  = Carbon::now();
            $date = $now->toDateString();
            $title = 'Detalle del Procesamiento de Solicitudes-';

            $directoryPath  = 'files/asientos/anticipo';

            $filePath = $directoryPath . '/' . $title . $date . '.xls';

            $data = [];

            if( Session::has( 'asientos' ) )
            {
                $data[ 'responses' ] = Session::get( 'asientos_anticipo' );
            }

            if( File::exists( public_path( $filePath ) ) )
            {
                $rows = Excel::selectSheetsByIndex( 0 )->load( public_path( $filePath ) )->getTotalRowsOfFile();
                if( $rows >= 0 )
                {
                    $data[ 'oldResponses' ] = Excel::selectSheetsByIndex( 0 )->load( public_path( $filePath ) )->get();
                }
            }


            if( ! isset( $data[ 'responses' ] ) && ! isset( $data[ 'oldResponses' ] ) )
            {
                return $this->warningException( 'No se pudo exportar el excel con las observaciones de las solicitudes procesadas' , __FUNCTION__ , __LINE__ , __FILE__ );
            }
            
            Excel::create( $title . $date , function( $excel ) use( $data )
            {
                $excel->sheet( 'solicitudes' , function( $sheet ) use( $data )
                {
                    $sheet->freezeFirstRow();
                    $sheet->setStyle( 
                        array(
                            'font' => 
                                array(
                                    'bold' => true
                                )
                            )
                        );
                    $sheet->loadView( 'Dmkt.Cont.Excel.seat_detail' , $data );
                });
            })->store( 'xls' , public_path( $directoryPath ) );
            return Response::download( $filePath );
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }
}