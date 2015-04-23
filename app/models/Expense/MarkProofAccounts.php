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
		Log::error( $id_cuenta_cont);
		Log::error( $id_cuenta_mkt);
		return MarkProofAccounts::leftJoin('DMKT_RG_MARCA b' , 'b.id ' , '=' , 'dmkt_rg_cuenta_gasto_marca.idmarca')->where('DMKT_RG_CUENTA_GASTO_MARCA.idcuentagasto' , $id_cuenta_cont)->where('dmkt_rg_cuenta_gasto_marca.idcuentafondo' , $id_cuenta_mkt)->select('b.*')->get();
	}  
}