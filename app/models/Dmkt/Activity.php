<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 18/08/14
 * Time: 12:33 PM
 */

namespace Dmkt;
use \Eloquent;
use \Dmkt\Solicitude;
use \Common\State;
use \Expense\Deposit;

class Activity extends Eloquent{

    protected $table = 'DMKT_RG_ACTIVIDAD';
    protected $primaryKey = 'IDACTIVIDAD';


    function searchId(){

        $lastId = Activity::orderBy('idactividad', 'DESC')->first();
        if($lastId == null){
            return 0;
        }else{
            return $lastId->idactividad;
        }

    }

    function solicitude(){

        return $this->hasOne('Dmkt\Solicitude','idsolicitud','idsolicitud');
    }

    function state(){

        return $this->hasOne('Common\State','idestado','idestado');
    }

    function deposit(){
        return $this->hasOne('Expense\Deposit','iddeposito','iddeposito');
    }


}