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


    public function Rm(){

        return $this->hasOne('Dmkt\Rm','iduser','id');
    }
    public function Sup(){

        return $this->hasOne('Dmkt\Sup','iduser','id');
    }
    public function Solicituds(){

        return $this->hasMany('Dmkt\Solicitude','iduser','id');

    }
}
