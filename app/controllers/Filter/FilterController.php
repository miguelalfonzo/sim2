<?php

namespace Filter;

use \Auth;
use \Request;
use \App;
use \Redirect;

class FilterController
{

	private function rolFilter( $rols )
	{
	    if( ! Auth::check() )
	    {
	    	if ( Request::ajax() )
	        { 
	            $warning = App::make('BaseController')->callAction( 'warningException' , array( 'Vuelva a acceder al sistema ( La sesion expiro )' , __FUNCTION__ , __LINE__ , __FILE__ ) );
	        	$warning[ status ] = 'Logout';
	        }
	        else
	        {
	            return Redirect::to( 'login' );        
	        }
	    }
	    elseif( is_null( Auth::user()->simApp ) && is_null( Auth::user()->bagoSimApp ) )
	    { 
	        if ( Request::ajax() )
	        { 
	            return App::make('BaseController')->callAction( 'warningException' , array( 'No tiene permiso para acceder al sistema' , __FUNCTION__ , __LINE__ , __FILE__ ) );
	        }
	        else
	        {
	            return Redirect::to( 'login' );        
	        }
	    }
	    elseif( ! is_null( $rols ) && ! in_array( Auth::user()->type , $rols ) )
	    {
	        if ( Request::ajax() )
	        { 
	            return App::make('BaseController')->callAction( 'warningException' , array( 'Su rol no tiene permiso para acceder a esta funcionalidad' , __FUNCTION__ , __LINE__ , __FILE__ ) );
	        }
	        else
	        {
	            return Redirect::to( 'show_user' );        
	        }
	    }
	}

	public function rep_sup()
	{
		return $this->rolFilter( [ REP_MED , SUP ] );
	}

	public function rep_sup_cont_tes()
	{
		return $this->rolFilter( [ REP_MED , SUP , CONT , TESORERIA ] );
	}

    public function rep_sup_gerProd_asisGer()
	{
		return $this->rolFilter( [ REP_MED , SUP , GER_PROD , ASIS_GER ] );
	}

	public function rep_sup_gerProd_gerProm_gerCom_gerGen_asisGer()
	{
	    return $this->rolFilter( [ REP_MED , SUP , GER_PROD , GER_PROM , GER_COM , GER_GER , ASIS_GER ] );
	}

	public function rep_sup_cont_gerProd_gerProm_gerCom_gerGen()
	{
		return $this->rolFilter( [ REP_MED , SUP , CONT , GER_PROD , GER_PROM , GER_COM , GER_GER ] );
	}

	public function rep_sup_cont_gerProd_gerProm_gerCom_gerGen_aGer()
	{
		return $this->rolFilter( [ REP_MED , SUP , CONT , GER_PROD , GER_PROM , GER_COM , GER_GER , ASIS_GER ] );
	}

	public function supervisor()
	{
		return $this->rolFilter( [ SUP ] );
	}

	public function sup_gerProd_gerProm_gerCom_gerGen()
	{
		return $this->rolFilter( [ SUP , GER_PROD , GER_PROM , GER_COM , GER_GER ] );
	}

	public function contabilidad()
	{
		return $this->rolFilter( [ CONT ] );
	}

	public function tesoreria()
	{
		return $this->rolFilter( [ TESORERIA ] );
	}

	public function gerentes()
	{
		return $this->rolFilter( [ GER_PROD , GER_PROM , GER_COM , GER_GER ] );
	}

	public function gerenteComercial()
	{
		return $this->rolFilter( [ GER_COM ] );
	}

	public function gerCom_cont()
	{
		return $this->rolFilter( [ GER_COM , CONT ] );
	}

	public function asistenteGerencia()
	{
		return $this->rolFilter( [ ASIS_GER ] );
	}

	public function system()
	{
		return $this->rolFilter( null );
	}

	public function admin()
	{
		return $this->rolFilter( [ 'A' ] );
	}
}