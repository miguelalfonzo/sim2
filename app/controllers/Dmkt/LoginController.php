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


    public function doLogin()
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'email'    => 'required|email', // make sure the email is an actual email
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
                'email' 	=> Input::get('email'),
                'password' 	=> Input::get('password')
            );

            // attempt to do the login
           // var_dump($userdata);
            //var_dump(Auth::attempt($userdata));die;
            if (Auth::attempt($userdata)) {
                $user = User::where('email',Input::get('email'))->first();
                Auth::login($user);
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