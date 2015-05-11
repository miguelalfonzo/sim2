<?php

namespace Dmkt;
use \Eloquent;

class InvestmentType extends Eloquent
{
    protected $table = 'DMKT_RG_TIPO_INVERSION';
    protected $primaryKey = 'id';

    protected static function order()
    {
    	return InvestmentType::orderBy('id','asc')->get();
    }
}