<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 14/08/14
 * Time: 05:51 PM
 */

namespace Common;
use \Eloquent;
use \Common\SubTypeActivity;

class TypeActivity extends Eloquent{

    protected $table = 'DMKT_RG_TIPO_ACTIVIDAD';
    protected $primaryKey = 'id';

    public function subtype(){

        return $this->hasMany('\Common\Fondo','idtipoactividad','idtipoactividad');
    }
}