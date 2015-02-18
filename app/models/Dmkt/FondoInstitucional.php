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


    protected $table = 'DMKT_RG_FONDOINSTITUCIONAL';
    protected $primaryKey = 'idfondo';

    function searchId(){

        $lastId = FondoInstitucional::orderBy('idfondo', 'DESC')->first();
        if($lastId == null){
            return 0;
        }else{
            return $lastId->idfondo;
        }

    }

    public function monthYear($period) {
        $month = substr($period, -2);
        $year = substr($period, 0, 4);
        return $month.'/'.$year;
    }

    public function deposit(){

        return $this->hasOne('Common\Deposit','idfondo','idfondo');
    }
}