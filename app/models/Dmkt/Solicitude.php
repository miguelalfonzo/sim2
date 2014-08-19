<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 07/08/14
 * Time: 01:47 PM
 */


namespace Dmkt;
use \Eloquent;


class Solicitude extends Eloquent{


    protected $table = 'DMKT_RG_SOLICITUD';
    protected $primaryKey = 'IDSOLICITUD';


    function searchId(){

        $lastId = Solicitude::orderBy('idsolicitud', 'DESC')->first();
         if($lastId == null){
             return 0;
         }else{
             return $lastId->idsolicitud;
         }

    }
    function subtype(){

       return  $this->hasOne('Common\SubTypeActivity','idsubtipoactividad','idsubtipoactividad');
    }

    function state(){

        return $this->hasOne('Common\State','idestado','idestado');
    }

    function typemoney(){

        return $this->hasOne('Common\TypeMoney','idtipomoneda','tipo_moneda');
    }

}
