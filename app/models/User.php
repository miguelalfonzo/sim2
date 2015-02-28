<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

    function searchId(){

        $lastId = User::orderBy('id', 'DESC')->first();
        if($lastId == null){
            return 0;
        }else{
            return $lastId->id;
        }

    }
    function person(){
        return $this->hasOne('Common\Person','iduser','id');
    }
    public function Rm(){
        return $this->hasOne('Dmkt\Rm','iduser','id');
    }
    public function Sup(){
        return $this->hasOne('Dmkt\Sup','iduser','id');
    }

    public function Solicituds(){
        return $this->hasMany('Dmkt\Solicitude','iduser','id');
    }

    public function GerProd(){
        return $this->hasOne('Dmkt\Manager','iduser','id');
    }

    public function type(){
        return $this->hasOne('Common\TypeUser','codigo','type');
    }

    public function apps(){
        return $this->hasMany('Common\UserApp' ,'iduser','id');
    }
    public function getName(){
        $username= '';

        $userType       = $this->type;
        if($userType == 'R'){
            $username .= $this->rm->nombres .' ';
            $username .= $this->rm->apellidos;
        }
        elseif($userType == 'S')
        {
            $username .= $this->sup->nombres .' ';
            $username .= $this->sup->apellidos;
        }
        elseif($userType == 'P'){
            $username = $this->gerProd->descripcion;
        }else{
            $username .= $this->person->nombres .' ';
            $username .= $this->person->apellidos;
        }
        return $username;
        
    }

}
