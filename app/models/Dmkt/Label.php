<?php

namespace Dmkt;
use \Eloquent;

class Label extends Eloquent
{
    protected $table = 'DMKT_RG_ETIQUETA';
    protected $primaryKey = 'id';

    protected static function order()
    {
    	return Label::orderBy('id','asc')->get();
    }
}