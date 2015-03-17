<?php

namespace Dmkt;
use \Eloquent;
class Account extends Eloquent
{
    protected $table = 'DMKT_RG_CUENTA';
    protected $primaryKey = 'idcuenta';
    public $incrementing = true;
    protected $fillable = array('idcuenta','nombre','num_cuenta','alias');

    function searchId(){

        $lastId = Account::orderBy('idcuenta', 'DESC')->first();
        if($lastId == null){
            return 0;
        }else{
            return $lastId->idcuenta;
        }

    }
}