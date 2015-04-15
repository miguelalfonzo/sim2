<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 09/09/14
 * Time: 04:11 PM
 */


namespace Dmkt;
use \Eloquent;
class SolicitudeGer extends Eloquent{

    protected $table = 'DMKT_RG_SOLICITUD_GERENTE';
    protected $primaryKey = 'id';

    function searchId(){
        $lastId = SolicitudeGer::orderBy('id', 'DESC')->first();
        if($lastId == null){
            return 0;
        }else{
            return $lastId->id;
        }

    }

}