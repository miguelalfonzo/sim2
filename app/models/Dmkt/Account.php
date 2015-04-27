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

    protected function fondoRelations()
    {
        return $this->hasMany( 'Expense\MarkProofAccounts' , 'idcuentafondo' , 'id');
    }

    protected static function getExpenseAccount( $cuenta_mkt )
    {
        return Account::leftJoin('DMKT_RG_CUENTA_GASTO_MARCA b' , 'b.idcuentafondo' , '=' , 'dmkt_rg_cuenta.id' )
        ->leftJoin('DMKT_RG_CUENTA c' , 'c.id' , '=' , 'b.idcuentagasto' )->select('c.*')->where('dmkt_rg_cuenta.num_cuenta' , $cuenta_mkt )->get();
    }

}