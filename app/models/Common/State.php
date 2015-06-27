<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 12/08/14
 * Time: 03:11 PM
 */
namespace Common;
use \Eloquent;

class State extends Eloquent
{
    protected $table = 'SIM_SUB_ESTADO';
    protected $primaryKey = 'id';

	public function rangeState()
	{
        return $this->hasOne( 'Common\StateRange' , 'id' , 'id_estado' );
    }

    protected function getCancelStates()
    {
    	return State::whereIn( 'id_estado' , array( PENDIENTE , DERIVADO , ACEPTADO ) )->lists( 'id' );
    }
}