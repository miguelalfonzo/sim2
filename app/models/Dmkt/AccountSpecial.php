<?php

namespace Dmkt;

use \Eloquent;

class AccountSpecial extends Eloquent 
{
    protected $table = 'CUENTA_ESPECIAL';
    protected $primaryKey = 'id';

    public static function getRefund()
    {
    	return AccountSpecial::find( 1 )->num_cuenta;
    }
}