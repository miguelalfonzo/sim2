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
	protected $table = 'OUTDVP.USERS';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

    function lastId()
    {
        $lastId = User::orderBy( 'id' , 'DESC' )->first();
        if( is_null( $lastId ) )
            return 0;
        else
            return $lastId->id;
    }

    protected static function getUserType( $userType ){
        return User::where( 'tipo' , $userType )->lists( 'id' );
    }
    
    protected function person(){
        return $this->hasOne('Users\Person','iduser','id');
    }
    public function rm(){
        return $this->hasOne('Users\Rm','iduser','id');
    }
    public function sup(){
        return $this->hasOne('Users\Sup','iduser','id');
    }

    public function solicituds(){
        return $this->hasMany('Dmkt\Solicitude','iduser','id');
    }

    public function gerProd(){
        return $this->hasOne('Users\Manager','iduser','id');
    }

    public function userType(){
        return $this->hasOne('Common\TypeUser','codigo','type');
    }

    public function apps(){
        return $this->hasMany('Common\UserApp' ,'iduser','id');
    }

    public function simApp(){
        return $this->hasOne('Common\UserApp' , 'iduser' , 'id' )->where( 'idapp' , SISTEMA_SIM );
    }

    public function getName()
    {
        $username = '';
        $userType = $this->type;
        if( $userType == REP_MED )
            $username .= $this->rm->full_name ;
        elseif( $userType == SUP )
            $username .= $this->sup->full_name;
        elseif( $userType == GER_PROD )
            $username = $this->gerProd->descripcion;
        else
            $username .= $this->person->full_name;
        return $username;
    }

}
