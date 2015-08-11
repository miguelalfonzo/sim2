<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 19/08/14
 * Time: 10:30 AM
 */


namespace Common;
use \Eloquent;
class TypeMoney extends Eloquent{

    protected $table = TB_TIPO_MONEDA;
    protected $primaryKey = 'id';

}