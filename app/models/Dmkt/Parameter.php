<?php

namespace Dmkt;
use \Eloquent;

class Parameter extends Eloquent{

    protected $table = 'DMKT_RG_PARAMETER';
    protected $primaryKey = 'id';

    protected static function getIGV()
    {
    	return Parameter::find(1)->value;
    }


}