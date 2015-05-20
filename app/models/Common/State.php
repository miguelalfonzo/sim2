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
    protected $table = 'SUB_ESTADO';
    protected $primaryKey = 'id';

	function rangeState()
	{
        return $this->hasOne( 'Common\StateRange' , 'id' , 'id_estado' );
    }
}