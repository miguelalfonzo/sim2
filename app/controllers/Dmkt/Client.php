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
	 		$act = Activity::where('tipo_cliente' , $clientType )->lists('id');
	 		$inv = InvestmentActivity::whereIn('id_actividad' , $act )->lists('id_inversion');
 			return $this->setRpta( array( 'id_inversion' => $inv , 'id_actividad' => $act  ) );
 		}
 		catch ( Exception $e )
 		{
 			return $this->internalException( $e , __FUNCTION__ );
 		}
    }

    public function getActivities()
    {
    	try
    	{
    		$inputs = Input::all();
    		if ( isset( $inputs['tipo_cliente'] ) )
    		{
    			$act = Activity::where( 'tipo_cliente' , $inputs['tipo_cliente'] )->lists('id');
    			$act = InvestmentActivity::where( 'id_inversion' , $inputs['id_inversion'] )->whereIn( 'id_actividad' , $act );
    		}
    		else
    			$act = InvestmentActivity::where( 'id_inversion' , $inputs['id_inversion'] );
    		return $this->setRpta( $act->lists('id_actividad') );
    	}
    	catch( Exception $e )
    	{
    		return $this->internalException( $e , __FUNCTION__ );
    	}
    }
}