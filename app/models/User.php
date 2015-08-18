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
	protected $table = TB_USUARIOS;

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

    protected static function getAsisGer()
    {
        return User::where( 'type' , ASIS_GER )->where( 'active' , 1 )->get();
    }

    protected static function getCont()
    {
        return User::where( 'type' , CONT )->where( 'active' , 1 )->get(); 
    }

    protected static function getUserType( $userType )
    {
        return User::where( 'type' , $userType )->lists( 'id' );
    }
    
    protected function Personal(){
        return $this->hasOne('Users\Personal','user_id','id');
    }
    
    public function rm(){
        return $this->hasOne('Users\Personal','user_id','id')->where('tipo', '=', 'RM');
        // return $this->hasOne('Users\Rm','iduser','id');
    }
    
    public function sup(){
        return $this->hasOne('Users\Personal','user_id','id')->where('tipo', '=', 'S');
    }

    public function solicituds(){
        return $this->hasMany('Dmkt\Solicitud','iduser','id');
    }

    public function gerProd(){
        // return $this->hasOne('Users\Manager','iduser','id');
        return $this->hasOne('Users\Personal','user_id','id')->where('tipo', '=', 'P');
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
        return ucwords(strtolower($this->personal->nombres)) .' '. ucwords(strtolower($this->personal->apellidos));
    }

    public function getFirstName(){
        return ucwords(strtolower($this->personal->nombres));
    }

    protected function assignTempUser()
    {
        return $this->hasOne( 'Users\TemporalUser' , 'id_user_temp' );
    }

    protected function assignedTempUser()
    {
        return $this->hasOne( 'Users\TemporalUser' , 'id_user' );
    } 

    public function tempId()
    {
        $tempUser = $this->assignedTempUser;
        if ( is_null( $tempUser) )
            return 0;
        else
            return $tempUser->id_user_temp;
    }

    public function tempType()
    {
        $tempUser = $this->assignedTempUser;
        if ( is_null( $tempUser) )
            return '';
        else
            return $tempUser->userTemp->type;
    }
    
}
