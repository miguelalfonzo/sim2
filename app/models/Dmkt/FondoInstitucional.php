<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 13/11/2014
 * Time: 05:52 PM
 */

namespace Dmkt;
use \Eloquent;
class FondoInstitucional extends Eloquent{


    protected $table = 'FONDOINSTITUCIONAL';
    protected $primaryKey = 'IDFONDO';

    function searchId(){

        $lastId = FondoInstitucional::orderBy('idfondo', 'DESC')->first();
        if($lastId == null){
            return 0;
        }else{
            return $lastId->idfondo;
        }

    }
}