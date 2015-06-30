<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 12/08/14
 * Time: 03:11 PM
 */
namespace Common;
use \Eloquent;

class StateRange extends Eloquent{

    protected $table = 'ESTADO';
    protected $primaryKey = 'id';

    protected function order()
    {
    	return StateRange::orderBy('id', 'ASC')->get();
    }
}