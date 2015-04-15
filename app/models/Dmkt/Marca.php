<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 13/08/14
 * Time: 12:13 PM
 */
namespace Dmkt;

class Marca extends \Eloquent {

    public $timestamps = false;
    protected $primaryKey = 'id';


    function manager(){

        return $this->hasOne('Dmkt\Manager','id','gerente_id');
    }



}