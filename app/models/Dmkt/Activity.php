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

        $lastId = Solicitude::orderBy('idsolicitud', 'DESC')->first();
        if($lastId == null){
            return 0;
        }else{
            return $lastId->idsolicitud;
        }

    }
    function subtype(){

        return  $this->hasOne('Dmkt\SubTypeActivity','idsubtipoactividad','sub_tipo_actividad');
    }

    function state(){

        return $this->hasOne('Dmkt\State','idestado','estado_idestado');
    }


}