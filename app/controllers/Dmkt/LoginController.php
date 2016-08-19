<?php

namespace Dmkt;

use \BaseController;
use \View;
use \DB;
use \Input;
use \Redirect;
use \Auth;
use \Validator;
use User;
use \Exception;
use \Session;

class LoginController extends BaseController{

    public function showLogin()
    {
        return View::make('Dmkt.login');
    }

    public function doLogin()
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'username' => 'required', // make sure the email is an actual email
            'password' => 'required|min:3' // password can only be alphanumeric and has to be greater than 3 characters
        );

        $validator = Validator::make( Input::all(), $rules);
        if ($validator->fails()) 
        {
            return Redirect::to( 'login' )
                ->withErrors( $validator ) // send back all errors to the login form
                ->withInput( Input::except('password') ); // send back the input (not the password) so that we can repopulate the form
        } 

        $userName = Input::get( 'username' );
        $password = Input::get( 'password' );

        $user = User::validateUsername( $userName );

        if( is_null( $user ) )
        {
            return Redirect::to( 'login' )->with( 'error_login' , true );       
        }
        else
        {
            if( is_null( $user->passbago ) )
            {
                // create our user data for the authentication
                $userdata = array(
                    'username'  => $userName,
                    'password'  => $password,
                    'active'    => 1
                );

                if ( Auth::attempt( $userdata ) )
                {
                    if( ! in_array( Auth::user()->type , [ REP_MED , SUP , GER_PROD , GER_PROM , GER_COM , GER_GER , CONT , TESORERIA , ASIS_GER , 'A' ] ) )
                    {
                        Auth::logout();
                        return View::make( 'Dmkt.login' )->with( array( 'message' => 'Rol no autorizado' ) );
                    }
                    else if( is_null( Auth::user()->simApp ) )
                    {
                        Auth::logout();
                        return View::make( 'Dmkt.login' )->with( array( 'message' => 'Usuario no autorizado para el SIM' ) );
                    }
                    else
                    {
                        Auth::user()->touch();
                        return Redirect::to( 'show_user' );
                    }
                }
                else
                {
                    return Redirect::to( 'login' )->with( 'error_login' , true );
                }
            }
            else
            {
                Auth::login( $user );
                if( is_null( Auth::user()->bagoSimApp ) )
                {
                    Auth::logout();
                    return View::make( 'Dmkt.login' )->with( array( 'message' => 'Usuario no autorizado para el SIM' ) );
                }
                else if( ! in_array( Auth::user()->type , [ REP_MED , SUP , GER_PROD , GER_PROM , GER_COM , GER_GER , CONT , TESORERIA , ASIS_GER , 'A' ] ) )
                {
                    Auth::logout();
                    return View::make( 'Dmkt.login' )->with( array( 'message' => 'Rol no autorizado' ) );
                }
                else
                {
                    $user = User::loginBagoUser( $userName , $password );
                    if( is_null( $user ) )
                    {
                        Auth::logout();
                        return Redirect::to( 'login' )->with( 'error_login' , true );
                    }
                    else
                    {
                        Auth::user()->touch();
                        return Redirect::to( 'show_user' );   
                    }
                }    
            }
        }  
    }

    public function doLogout()
    {
        Auth::logout(); // log the user out of our application
        Session::flush();
        return Redirect::to('login'); // redirect the user to the login screen
    }

}