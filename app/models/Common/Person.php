<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 06/10/14
 * Time: 04:16 PM
 */

namespace Common;
use \Eloquent;
class Person extends Eloquent{

    protected $table = 'PERSONAS';
    protected $primaryKey = 'idpersona';

    function searchId(){

        $lastId = Person::orderBy('idpersona', 'DESC')->first();
        if($lastId == null){
            return 0;
        }else{
            return $lastId->idpersona;
        }

    }
}