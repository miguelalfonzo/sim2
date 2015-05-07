<?php

namespace Dmkt;
use \Eloquent;
class Account extends Eloquent
{
    protected $table = 'DMKT_RG_CUENTA';
    protected $primaryKey = 'id';
    
    public function lastId()
    {
        $lastId = Account::orderBy('id', 'desc')->first();
        if( $lastId == null )
            return 0;
        else
            return $lastId->id;
    }

    protected function bagoAccount()
    {
        return $this->belongsTo('Expense\PlanCta' , 'num_cuenta' , 'ctactaextern' );
    }

    public function typeAccount()
    {
        return $this->hasOne('Expense\AccountType','id','idtipocuenta');
    }

    protected function fondo()
    {
        return $this->belongsTo('Common\Fondo' , 'id' , 'idcuenta');
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
        return Account::leftJoin('DMKT_RG_CUENTA_GASTO_MARCA b' , 'b.num_cuenta_fondo' , '=' , 'dmkt_rg_cuenta.num_cuenta' )
        ->leftJoin('DMKT_RG_CUENTA c' , 'c.num_cuenta' , '=' , 'b.num_cuenta_gasto' )->select('c.*')->where('dmkt_rg_cuenta.num_cuenta' , $cuenta_mkt )->get();
    }

}