<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 29/08/14
 * Time: 05:28 PM
 */


namespace Expense;
use \Eloquent;
use \Common\State;
use \Common\TypeMoney;
use \Common\SubTypeActivity;
class ExpenseItem extends Eloquent{

    protected $table= 'DMKT_RG_GASTOS_ITEM';
    protected $primaryKey = null;
    public $incrementing = false;



}