<?php

namespace Parameter;

use \Eloquent;

class Parameter extends Eloquent
{
    protected $table= 'PARAMETRO';
    protected $primaryKey = 'id';

    protected static function order()
    {
    	return Parameter::orderBy( 'id' , 'ASC' )->get();
    }

}