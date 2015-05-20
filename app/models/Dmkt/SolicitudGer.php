<?php

namespace Dmkt;

use \Eloquent;

class SolicitudGer extends Eloquent
{
    protected $table = 'SOLICITUD_GERENTE';
    protected $primaryKey = 'id';

    public function lastId()
    {
        $lastId = SolicitudeGer::orderBy( 'id' , 'DESC' )->first();
        if( is_null( $lastId ) )
            return 0;
        else
            return $lastId->id;
    }

    protected static function deleteSolicitud( $idSolicitud )
    {
        SolicitudeGer::where( 'id_solicitud' , $idSolicitud )->delete();
    }

}