<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 18/08/14
 * Time: 12:33 PM
 */

namespace Dmkt;
use \Eloquent;

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

        return $this->hasOne('Dmkt\State','idestado','idestado');
    }


}