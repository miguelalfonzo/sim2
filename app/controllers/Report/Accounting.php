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
        elseif( $type == 'completo' )
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
    
            $data = $this->getData( $inputs );

            if( isset( $data[ status ] ) && $data[ status ] == error )
            {
                return $data;
            }

            $format = $this->getFormat( $inputs[ 'type' ] );
            
            $rpta = $this->setRpta( $data );
            
            $rpta = array_merge( $rpta , $format );

            return $rpta;
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
	}

    private function getData( $inputs )
    {
        $dates  = [ 'start' => $inputs[ 'fecha_inicio' ] , 'end' => $inputs[ 'fecha_final' ] ];

        if( $inputs[ 'type' ] == 'monto' )
        {
            $data = DataList::getAmountReport( $inputs[ 'colaborador' ] , $dates , $inputs[ 'num_cuenta' ] , $inputs[ 'solicitud_id' ] );
        }
        else
        {
            $data = \DB::table( 'REP_COMP' )->get();
        }
        return $data;
    }

    private function getFormat( $type )
    {
        if( $type == 'monto' )
        {
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
            $rowsGroup = [];
        }
        else
        {
            $columns =
                [
                    [ 'title' => '#' , 'data' => 'id' , 'className' => 'text-center' ],
                    [ 'title' => 'Mon Dep.' , 'data' => 'total' , 'className' => 'text-center' ],
                    [ 'title' => 'Operacion' , 'data' => 'num_transferencia' , 'className' => 'text-center' ],
                    [ 'title' => 'Anticip' , 'data' => 'num_asie_ant' , 'className' => 'text-center' ],
                    [ 'title' => 'Regular' , 'data' => 'num_asie_reg' , 'className' => 'text-center' ],
                    [ 'title' => 'Comprob' , 'data' => 'comp' , 'className' => 'text-center' ],
                    [ 'title' => 'RUC' , 'data' => 'ruc' , 'className' => 'text-center' ],
                    [ 'title' => 'Razon' , 'data' => 'razon' , 'className' => 'text-center' ],
                    [ 'title' => 'N°' , 'data' => 'num' , 'className' => 'text-center' ],
                    [ 'title' => 'Fec' , 'data' => 'fecha_movimiento' , 'className' => 'text-center' ],
                    [ 'title' => 'Desc' , 'data' => 'doc_desc' , 'className' => 'text-center' ],
                    [ 'title' => 'Sub Tot' , 'data' => 'sub_tot' , 'className' => 'text-center' ],
                    [ 'title' => 'Impuesto Servicio' , 'data' => 'imp_serv' , 'className' => 'text-center' ],
                    [ 'title' => 'IGV' , 'data' => 'igv' , 'className' => 'text-center' ],
                    [ 'title' => 'Reparo' , 'data' => 'reparo' , 'className' => 'text-center' ],
                    [ 'title' => 'Retencion' , 'data' => 'retencion' , 'className' => 'text-center' ],
                    [ 'title' => 'Detraccion' , 'data' => 'detraccion' , 'className' => 'text-center' ],
                    [ 'title' => 'Total' , 'data' => 'monto' , 'className' => 'text-center' ],
                    
                    [ 'title' => 'D. Monto' , 'data' => 'importe' , 'className' => 'text-center' ]
                ];
            $rowsGroup = [ 0 , 1 , 2 , 3 , 4 , 5 , 6 , 7, 8 , 9 , 10 , 11 , 12 ,13 , 14 , 15 , 16 , 17 , 18 ];
        }
        return [ 'columns' => $columns , 'rowsGroup' => $rowsGroup ];
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