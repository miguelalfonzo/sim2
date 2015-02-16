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
        // show the form
        return View::make('Dmkt.login');
    }

    public function test(){
        return View::make('Dmkt.test');
    }


    public function doLogin()
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'username'    => 'required', // make sure the email is an actual email
            'password' => 'required|min:3' // password can only be alphanumeric and has to be greater than 3 characters
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);

        // if the validator fails, redirect back to the form

        if ($validator->fails()) {
            return Redirect::to('login')
                ->withErrors($validator) // send back all errors to the login form
                ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        } else {

            // create our user data for the authentication
            $userdata = array(
                'username' 	=> Input::get('username'),
                'password' 	=> Input::get('password')
            );

            if (Auth::attempt($userdata) && Auth::user()->active == 1 ) {
                $apps =  Auth::user()->apps;
                $aplication = false;
                foreach($apps as  $app){
                    if($app->idapp == 2) // 2 : Aplicacion descargo de marketing
                        $aplication = true;
                }
                if($aplication){
                    $typeUser = Auth::user()->type;

                    if($typeUser == 'R'){
                        return Redirect::to('show_rm');
                    }
                    if($typeUser == 'S'){
                        return Redirect::to('show_sup');
                    }
                    if($typeUser == 'P'){
                        return Redirect::to('show_gerprod');
                    }
                    if($typeUser == 'C'){
                        return Redirect::to('show_cont');
                    }
                    if($typeUser == 'T'){
                        return Redirect::to('show_tes');
                    }
                    if($typeUser == 'G'){
                        return Redirect::to('show_gercom');
                    }
                    if($typeUser == 'AG'){
                        return Redirect::to('registrar-fondo');
                    }
                }else{

                    return Redirect::to('login'); // no tiene permiso para esta aplicacion
                }


            } else {

                // validation not successful, send back to form
                return Redirect::to('login');

            }

        }
    }

    public function doLogout()
    {
        Auth::logout(); // log the user out of our application
        return Redirect::to('login'); // redirect the user to the login screen
    }



}