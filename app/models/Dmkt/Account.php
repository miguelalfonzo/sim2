<?php

namespace Dmkt;
use \Eloquent;
class Account extends Eloquent{

    protected $fillable = array('nombre', 'numcuenta');
    protected $table = 'DMKT_RG_CUENTA';
    protected $primaryKey = 'idcuenta';


}