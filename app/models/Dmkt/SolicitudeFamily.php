<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 13/08/14
 * Time: 10:43 AM
 */

namespace Dmkt;
use \Eloquent;

class SolicitudeFamily extends Eloquent{


    protected $table = 'DMKT_RG_SOLICITUD_FAMILIA';
    protected $primaryKey = 'IDSOLICITUD_FAMILIA';
    public $timestamps = false;

    function searchId(){
        $lastId = SolicitudeFamily::orderBy('idsolicitud_familia', 'DESC')->first();
        if($lastId == null){
            return 0;
        }else{
            return $lastId->idsolicitud_familia;
        }

    }

    public function marca(){

        return $this->hasOne('Dmkt\Marca','id','idfamilia');
    }
}