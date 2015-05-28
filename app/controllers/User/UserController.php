<?php

namespace User;

use \BaseController;
use \Input;
use \Exception;
use \Log;
use \Users\TemporalUser;
use \Auth;

class UserController extends BaseController
{

	public function assignTemporalUser()
	{
		$inputs = Input::all();
		$tempUser = TemporalUser::getAssignment( Auth::user()->id );
		if ( is_null( $tempUser ) )
		{
			$tempUser = new TemporalUser;
			$tempUser->id = $tempUser->lastId() + 1;
			$tempUser->id_user = $inputs[ 'iduser' ];
			$tempUser->id_temp = Auth::user()->id;
			if ( ! $tempUser->save() )
				return $this->warningException( 'Error al grabar la asignacion en la Base de Datos' , __FUNCTION__ , __LINE__ , __FILE__ );
			else
				return $this->setRpta();
		}
		else
			return $this->warningException( 'No puede realizar la asignacion porque ya ha realizado una.' , __FUNCTION__ , __LINE__ , __FILE__ );
	}

	public function removeTemporalUSer()
	{
		$tempUser = TemporalUser::getAssignment( Auth::user()->id );
		/*$tempUser->id_temp = 39;
		$tempUser->save();
		return $this->setRpta();*/
		if ( is_null( $tempUser ) )
			return $this->warningException( 'No tiene asignacion para eliminar' , __FUNCTION__ , __LINE__ , __FILE__ );
		else
			if ( ! $tempUser->delete() )
				return $this->warningException( 'No se pudo procesar la eliminacion en la Base de Datos' , __FUNCTION__ , __LINE__ , __FILE__ );
			else
				return $this->setRpta();
	}
}