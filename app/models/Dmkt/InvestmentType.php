<?php

namespace Dmkt;
use \Eloquent;

class InvestmentType extends Eloquent
{
    protected $table = 'TIPO_INVERSION';
    protected $primaryKey = 'id';

    protected static function order()
    {
    	return InvestmentType::orderBy('id','asc')->get();
    }
}