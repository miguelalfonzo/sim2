<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 06/10/14
 * Time: 11:21 AM
 */
namespace Common;
use \Eloquent;

class TypeUser extends Eloquent{

    protected $table = 'TIPO_USUARIO';
    protected $primaryKey = 'codigo';

}