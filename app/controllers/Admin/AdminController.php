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

use Symfony\Component\Console\Input\Input;
use \View;
use \DB;
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

    function objectToArray($object)
    {
        $array = array();
        foreach ($object as $member => $data) {
            $array[$member] = $data;
        }
        return $array;
    }

    public function register(){

        $users = User::all();
        $typesUser = TypeUser::all();
        $data = [
            'users' => $users,
            'types' =>$typesUser
        ];
        return View::make('Admin.register_user',$data);

    }

    public function edit($id){

        $users = User::all();
        $user = User::find($id);
        $typeUser = TypeUser::all();
        $data = [
            'user' => $user,
            'types' => $typeUser,
            'users' => $users
        ];
        return View::make('Admin.register_user',$data);
    }

    public function formEditUser(){
        $inputs = Input::all();

        $rules = array(
            'username'         => 'required',
            'first_name'             => 'required',
            'last_name'             => 'required', // just a normal required validation
            'email'            => 'required|email', 	// required and must be unique in the ducks table

        );


        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {

            // get the error messages from the validator
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            return $messages;
        }else{

            $iduser = $inputs['iduser'];
            $user = User::where('id',$iduser)->first();
            $user->username = $inputs['username'];
            $user->email = $inputs['email'];
            if(isset($inputs['password']))
            $user->password = Hash::make($inputs['password']);
            $typeUser = $inputs['type'];
            $user->type = $inputs['type'];
            //$data = $this->objectToArray($user);
            //$user->update($data);
            $user->save();
            if($typeUser == 'R'){
                $idRm = $user->rm->idrm;
                $rm = Rm::where('idrm',$idRm);
                $rm->nombres = $inputs['first_name'];
                $rm->apellidos = $inputs['last_name'];
                $rm->iduser = $iduser;
                $data = $this->objectToArray($rm);
                $rm->update($data);
            }else if($typeUser == 'S'){

                $idSup = $user->sup->idsup;
                $sup = Sup::where('idsup',$idSup);
                $sup ->nombres = $inputs['first_name'];
                $sup ->apellidos = $inputs['last_name'];
                $sup ->iduser = $iduser;
                $data = $this->objectToArray($sup);
                $sup ->update($data);

            }else if($typeUser == 'P'){

                $idGerProd = $user->gerprod->id;
                $ger = Manager::where('id',$idGerProd);
                $ger->descripcion = $inputs['first_name'].' '.$inputs['last_name'];
                $ger->iduser = $iduser;
                $data = $this->objectToArray($ger);
                $ger->update($data);

            }else
            {
                $idPerson = $user->first()->person->idpersona;
                $person = Person::where('idpersona',$idPerson);
                $person->nombres = $inputs['first_name'];
                $person->apellidos = $inputs['last_name'];
                $person->iduser = $iduser;
                $data = $this->objectToArray($person);
                $person->update($data);

            }

            return Redirect::to('register')->with('message','Actualizacion de Usuario Satisfactorio');
        }

    }

    public function formRegister(){

        $inputs = Input::all();
        $rules = array(
            'username'         => 'required|unique:users',
            'first_name'             => 'required',
            'last_name'             => 'required', // just a normal required validation
            'email'            => 'required|email|unique:users', 	// required and must be unique in the ducks table
            'password'         => 'required',

        );
        //dd($inputs);

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {

            // get the error messages from the validator
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            return Redirect::to('register')->with('errors', $messages);
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

            return Redirect::to('register')->with('message','Registro de Usuario Satisfactorio');
        }



    }

    public function activeUser(){
        $iduser = Input::get('iduser');
        //$user = User::where('id',$iduser)->update(array('active'=>1));
        $user = User::find($iduser);
        $user->active = 1;
        $user->save();

        return 'ok';
    }

    public function lookUser(){
        $iduser = Input::get('iduser');
        //$user = User::where('id',$iduser)->update(array('active'=>1));
        $user = User::find($iduser);
        $user->active = 0;
        $user->save();
        return 'ok';
    }

}