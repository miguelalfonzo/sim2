<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 19/09/14
 * Time: 02:39 PM
 */

namespace Common;
use \Eloquent;

class TypePayment extends Eloquent{

    protected $table = TB_TIPO_PAGO;
    protected $primaryKey = 'id';



}