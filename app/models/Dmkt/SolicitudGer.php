<?php

namespace Dmkt;

use \Eloquent;

class SolicitudGer extends Eloquent
{
    protected $table = 'SOLICITUD_GERENTE';
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