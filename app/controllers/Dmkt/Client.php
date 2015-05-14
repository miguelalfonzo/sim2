<?php

namespace Dmkt;

use \BaseController;
use \Input;

class Client extends BaseController
{

    public function getInvestmentActivity()
    {
        $inputs = Input::all();
        if ( $inputs['tabla'] === 'FICPEF.PERSONAJUR' )
        	$clientType = 2;
        else if ( $inputs['tabla'] === 'FICPE.PERSONAFIS' )
 			$clientType = 1;
 		$act = SolicitudActivity::where('tipo_cliente' , $clientType )->lists('id');
 		$inv = InvestmentActivity::whereIn('id_actividad' , $act )->lists('id_inversion');
 		return $this->setRpta( array( 'id_inversion' => $inv , 'id_actividad' => $act  ) );

    }
}