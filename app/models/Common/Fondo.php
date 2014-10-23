<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 15/08/14
 * Time: 09:37 AM
 */
namespace Common;
use \Eloquent;
use \Common\TypeActivity;

class Fondo extends Eloquent{

    protected $table = 'DMKT_RG_FONDOS';
    protected $primaryKey = 'idfondo';

}