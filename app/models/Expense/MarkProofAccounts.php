<?php

namespace Expense;

use \Eloquent;
use \Log;
class MarkProofAccounts extends Eloquent
{
    protected $table= 'DMKT_RG_CUENTA_GASTO_MARCA';
    protected $primaryKey = 'id';
 

	protected static function getMarks( $id_cuenta_mkt , $id_cuenta_cont )
	{
		return MarkProofAccounts::leftJoin('DMKT_RG_MARCA b' , 'b.id ' , '=' , 'dmkt_rg_cuenta_gasto_marca.idmarca')->where('DMKT_RG_CUENTA_GASTO_MARCA.idcuentagasto' , $id_cuenta_cont)->where('dmkt_rg_cuenta_gasto_marca.idcuentafondo' , $id_cuenta_mkt )->select( 'b.*')->get();
	} 

	protected static function listExpenses( $id_cuenta_mkt )
	{
		return MarkProofAccounts::where('idcuentafondo' , $id_cuenta_mkt)->with( 'accountFondo' , 'accountExpense' , 'mark' , 'document')->get();
	}

	public function accountExpense()
	{
		return $this->hasOne( 'Dmkt\Account' , 'id' , 'idcuentagasto' );
	}

	public function mark()
	{
		return $this->hasOne( 'Dmkt\Marca' , 'id' , 'idmarca' );
	}

	public function document()
	{
		return $this->hasOne( 'Expense\Proof' , 'id' , 'iddocumento' );
	}

	public function accountFondo()
	{
		return $this->hasOne( 'Dmkt\Account' , 'id' , 'idcuentafondo' );
	}
}