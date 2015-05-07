<?php

namespace Expense;

use \Eloquent;
use \Log;
class MarkProofAccounts extends Eloquent
{
    protected $table= 'DMKT_RG_CUENTA_GASTO_MARCA';
    protected $primaryKey = 'id';
 
    public function lastId()
    {
    	$lastId = MarkProofAccounts::orderBy('id','desc')->first();
		if( $lastId == null )
            return 0;
        else
            return $lastId->id;
    }

	protected static function getMarks( $num_cuenta_mkt , $num_cuenta_expense )
	{
		return MarkProofAccounts::where('DMKT_RG_CUENTA_GASTO_MARCA.num_cuenta_gasto' , $num_cuenta_expense )->where('dmkt_rg_cuenta_gasto_marca.num_cuenta_fondo' , $num_cuenta_mkt )->select( 'marca_codigo')->get();
	} 

	protected static function listData( $num_cuenta )
	{
		return MarkProofAccounts::where( 'num_cuenta_fondo' , $num_cuenta )->with( 'accountFondo' , 'accountExpense' , 'mark' , 'document' , 'bagoAccountExpense' )->get();
	}

	public function accountExpense()
	{
		return $this->hasOne( 'Dmkt\Account' , 'id' , 'idcuentagasto' );
	}

	public function mark()
	{
		return $this->hasOne( 'Expense\Mark' , 'id' , 'idmarca' );
	}

	public function document()
	{
		return $this->hasOne( 'Expense\Proof' , 'id' , 'iddocumento' );
	}

	public function accountFondo()
	{
		return $this->hasOne( 'Common\Fondo' , 'num_cuenta' , 'num_cuenta_fondo' );
	}

	protected function bagoAccountFondo()
	{
		return $this->hasOne( 'Expense\PlanCta' , 'ctactaextern' , 'num_cuenta_fondo' );
	}

	public function bagoAccountExpense()
	{
		return $this->hasOne( 'Expense\PlanCta' , 'ctactaextern' , 'num_cuenta_gasto' );
	}
}