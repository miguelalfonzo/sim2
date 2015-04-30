<?php

namespace Dmkt;

class Marca extends \Eloquent {

    public $timestamps = false;
    protected $primaryKey = 'id';


    function manager(){

        return $this->hasOne('Dmkt\Manager','id','gerente_id');
    }



}