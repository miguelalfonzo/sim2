<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 06/10/14
 * Time: 09:45 AM
 */

namespace Admin;

use \BaseController;
use Dmkt\Rm;
use Dmkt\Sup;
use Dmkt\Manager;

use \View;
use \DB;
use \Input;
use \Redirect;
use \Auth;
use \Validator;
use \Common\TypeUser;
use \Common\Person;
use User;
use \Hash;
use \Session;


class AdminController extends BaseController
{

    public function register(){

        $users = User::all();
        $typesUser = TypeUser::all();
        $data = [
            'users' => $users,
            'types' =>$typesUser
        ];
        return View::make('Admin.register_user',$data);

    }

    public function form_register(){

        $inputs = Input::all();

        $rules = array(
            'username'         => 'required|unique:users',
            'first_name'             => 'required',
            'last_name'             => 'required', // just a normal required validation
            'email'            => 'required|email|unique:users', 	// required and must be unique in the ducks table
            'password'         => 'required',

        );


        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {

            // get the error messages from the validator
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            return $messages;
        }else{

            $user = new User();
            $iduser = $user->searchId() + 1;
            $user->id = $iduser;
            $user->username = $inputs['username'];
            $user->email = $inputs['email'];
            $user->password = Hash::make($inputs['password']);
            $typeUser = $inputs['type'];
            $user->type = $inputs['type'];
            $user->save();
            if($typeUser == 'R'){

                $rm = new Rm;
                $rm->idrm = $rm->searchId() + 1;
                $rm->nombres = $inputs['first_name'];
                $rm->apellidos = $inputs['last_name'];
                $rm->iduser = $iduser;
                $rm->save();
            }else if($typeUser == 'S'){
                $sup = new Sup;
                $sup ->idsup = $sup->searchId() + 1;
                $sup ->nombres = $inputs['first_name'];
                $sup ->apellidos = $inputs['last_name'];
                $sup ->iduser = $iduser;
                $sup ->save();
            }else if($typeUser == 'P'){
                $ger = new Manager;
                $ger->id = $ger->searchId() + 1;
                $ger->descripcion = $inputs['first_name'].' '.$inputs['last_name'];
                $ger->iduser = $iduser;
                $ger->save();

            }else
            {
                $person = new Person;
                $person->idpersona = $person->searchId() + 1;
                $person->nombres = $inputs['first_name'];
                $person->apellidos = $inputs['last_name'];
                $person->iduser = $iduser;
                $person->save();

            }

            return  'SI';
        }



    }



}