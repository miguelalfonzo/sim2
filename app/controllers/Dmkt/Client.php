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
	 		$act =  Activity::where('tipo_cliente' , $clientType )->has( 'investmentActivity' )->orderBy( 'nombre' , 'ASC' )->get();
	 		$inv =  InvestmentType::whereHas( 'investmentActivity' , function( $query ) use( $clientType )
                    {
                        $query->whereHas( 'activity' , function( $query ) use( $clientType )
                        {
                            $query->where( 'tipo_cliente' , $clientType );
                        });
                    })->orderBy( 'nombre' , 'ASC' )->get();
            $dInv = $this->setRpta( $inv , 'SELECCIONE LA INVERSION' );
            $dAct = $this->setRpta( $act , 'SELECCIONE LA ACTIVIDAD' );
            return $this->setRpta( array( 'Investments' => $dInv , 'Activities' => $dAct  ) );
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
                $act    =   Activity::where( 'tipo_cliente' , $inputs['tipo_cliente'] )
                            ->whereHas( 'investmentActivity' , function( $query ) use( $inputs )
                            {
                                $query->where( 'id_inversion' , $inputs[ 'id_inversion' ] );
                            })->orderBy( 'nombre' , 'ASC' )->get();
            }
    		else
            {   
                $act    =   Activity::whereHas( 'investmentActivity' , function( $query ) use( $inputs )
                            {
                                $query->where( 'id_inversion' , $inputs[ 'id_inversion' ] );
                            })->orderBy( 'nombre' , 'ASC' )->get();
            }
            $dAct = $this->setRpta( $act , 'SELECCIONE LA ACTIVIDAD' );
    		return $this->setRpta( $dAct );
    	}
    	catch( Exception $e )
    	{
    		return $this->internalException( $e , __FUNCTION__ );
    	}
    }
}