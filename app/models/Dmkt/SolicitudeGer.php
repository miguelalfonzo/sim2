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

    protected $table = 'DMKT_RG_SOLICITUD_GERENTES';
    protected $primaryKey = 'IDSOLICITUD_GERENTE';

    function searchId(){
        $lastId = SolicitudeGer::orderBy('idsolicitud_gerente', 'DESC')->first();
        if($lastId == null){
            return 0;
        }else{
            return $lastId->idsolicitud_gerente;
        }

    }

}