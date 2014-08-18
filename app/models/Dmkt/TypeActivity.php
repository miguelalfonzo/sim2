<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 14/08/14
 * Time: 05:51 PM
 */

namespace Dmkt;
use \Eloquent;

class TypeActivity extends Eloquent{

    protected $table = 'DMKT_RG_TIPO_ACTIVIDAD';
    protected $primaryKey = 'IDTIPOACTIVIDAD';

    public function subtype(){

        return $this->hasMany('Dmkt\SubTypeActivity','idtipoactividad','idtipoactividad');
    }
}