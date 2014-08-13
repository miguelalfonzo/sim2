<?php


namespace Dmkt;
use \Eloquent;
class SolicitudeClient extends Eloquent{


    protected $table = 'DMKT_RG_SOLICITUD_CLIENTES';
    protected $primaryKey = 'IDSOLICITUD_CLIENTES';
    public $timestamps = false;
}