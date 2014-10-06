<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 06/10/14
 * Time: 09:45 AM
 */

namespace Admin;

use \BaseController;
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

        $user = new User();
        $iduser = $user->searchId() + 1;
        $user->id = $iduser;
        $user->username = $inputs['username'];
        $user->email = $inputs['email'];
        $user->password = Hash::make($inputs['password']);
        $user->type = $inputs['type'];
        $user->save();

        $person = new Person;
        $person->idpersona = $person->searchId() + 1;
        $person->nombres = $inputs['first_name'];
        $person->apellidos = $inputs['last_name'];
        $person->iduser = $iduser;
        $person->save();


        return  'SI';
    }



}