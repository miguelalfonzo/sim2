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
    protected $primaryKey = 'ID';

    public function searchId()
    {
        $lastId = SolicitudeFamily::orderBy('id', 'DESC')->first();
        if($lastId == null)
            return 0;
        else
            return $lastId->id;
    }

    public function marca()
    {
        return $this->hasOne('Dmkt\Marca','id','idfamilia');
    }
}