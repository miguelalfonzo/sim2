<?php

namespace Dmkt;

use \BaseController;
use \Input;
use \Exception;

class Client extends BaseController
{

    public function getInvestmentActivity()
    {
    	try
    	{
	        $inputs = Input::all();
	        $clientType = $inputs[ 'tipo_cliente' ];
	 		$act = SolicitudActivity::where('tipo_cliente' , $clientType )->lists('id');
	 		$inv = InvestmentActivity::whereIn('id_actividad' , $act )->lists('id_inversion');
 			return $this->setRpta( array( 'id_inversion' => $inv , 'id_actividad' => $act  ) );
 		}
 		catch ( Exception $e )
 		{
 			return $this->internalException( $e , __FUNCTION__ );
 		}
    }
}