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
        return $lastId->idsolicitud;
    }
}