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
        
        // create our user data for the authentication
        $userdata = array(
            'username' 	=> Input::get('username'),
            'password' 	=> Input::get('password'),
            'active'    => 1
        );
        if ( Auth::attempt( $userdata ) )
        {
            if ( is_null( Auth::user()->simApp ) )
            {
                Auth::logout();
                return View::make( 'Dmkt.login' )->with( array( 'message' => 'Usuario no autorizado' ) );
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

    public function doLogout()
    {
        Auth::logout(); // log the user out of our application
        return Redirect::to('login'); // redirect the user to the login screen
    }

}