<?php

namespace Dmkt;
use \Eloquent;

class SolicitudFamily extends Eloquent
{
    protected $table = 'DMKT_RG_SOLICITUD_FAMILIA';
    protected $primaryKey = 'id';

    public function lastId()
    {
        $lastId = SolicitudFamily::orderBy('id', 'DESC')->first();
        if( $lastId == null )
            return 0;
        else
            return $lastId->id;
    }

    public function marca()
    {
        return $this->hasOne('Dmkt\Marca','id','idfamilia');
    }
}