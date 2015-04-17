<?php

namespace Dmkt;
use \Eloquent;
class Account extends Eloquent
{
    protected $table = 'DMKT_RG_CUENTA';
    protected $primaryKey = 'id';
    
    protected function searchId()
    {
        $lastId = Account::orderBy('id', 'DESC')->first();
        if($lastId == null)
            return 0;
        else
            return $lastId->id;
    }

    public function typeAccount()
    {
        return $this->hasOne('Expense\AccountType','id','idtipocuenta');
    }

    protected function typeMoney()
    {
        return $this->hasOne('Common\TypeMoney','id','idtipomoneda');
    }

    public static function banks()
    {
        $banks = Account::whereHas('typeAccount' , function( $q )
        {
            $q->where('nombre','BANCOS');
        })->get();
        return $banks;
    }

}