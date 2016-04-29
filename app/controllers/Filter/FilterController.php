<?php

namespace Filter;

use \Auth;
use \Request;
use \App;

class FilterController
{

	private function rolFilter( $rols )
  	{
	    if( ! Auth::check() )
	    {
	        if ( Request::ajax() )
	        { 
	            return App::make('BaseController')->callAction( 'warningException' , array( 'Vuelva a acceder al sistema ( La sesion expiro )' , __FUNCTION__ , __LINE__ , __FILE__ ) );
	        }
	        else
	        {
	            return Redirect::to( 'login' );        
	        }
	    }
	    elseif( is_null( Auth::user()->simApp ) )
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
	    elseif ( ! in_array( Auth::user()->type , $rols ) )
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

  	public function representante()
  	{
  		return $this->rolFilter( [ REP_MED ] );
  	}

  	public function representante_supervisor()
  	{
  		return $this->rolFilter( [ REP_MED , SUP ] );
  	}

  	public function representante_supervisor_contabilidad()
  	{
  		return $this->rolFilter( [ REP_MED , SUP , CONTABILIDAD ] );
  	}
}