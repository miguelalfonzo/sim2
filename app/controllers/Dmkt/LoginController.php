<?php

namespace Dmkt;

use \Common\SubTypeActivity;
use \BaseController;
use \View;
use \DB;
use \Input;
use \Redirect;
use \Auth;
use \Validator;
use User;

class LoginController extends BaseController{

    public function showLogin()
    {
        return View::make('Dmkt.login');
    }

    public function doLogin()
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'username'    => 'required', // make sure the email is an actual email
            'password' => 'required|min:3' // password can only be alphanumeric and has to be greater than 3 characters
        );

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) 
        {
            return Redirect::to('login')
                ->withErrors($validator) // send back all errors to the login form
                ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        } 
        else 
        {
            // create our user data for the authentication
            $userdata = array(
                'username' 	=> Input::get('username'),
                'password' 	=> Input::get('password')
            );

            if (Auth::attempt($userdata) && Auth::user()->active == 1 ) 
            {
                $apps =  Auth::user()->apps;
                $aplication = false;
                foreach($apps as  $app)
                {
                    if($app->idapp == 2) // 2 : Aplicacion descargo de marketing
                        $aplication = true;
                }
                if($aplication)
                {
                    $typeUser = Auth::user()->type;
                    if( in_array( $typeUser, array( REP_MED , SUP , GER_PROD , GER_COM , CONT , TESORERIA , ASIS_GER ) ) )
                        return Redirect::to('show_user');
                   /* else if($typeUser == ASIS_GER)
                        return Redirect::to('registrar-fondo');*/
                    else
                        return View::make('Dmkt.login')->with( array('message' => 'Usuario no autorizado') );
                }
                else
                    return View::make('Dmkt.login')->with( array('message'=> 'Usuario no autorizado') );
            } 
            else
                return Redirect::to('login')->with('error_login', true);
        }
    }

    public function doLogout()
    {
        Auth::logout(); // log the user out of our application
        return Redirect::to('login'); // redirect the user to the login screen
    }

}