<?php


namespace Dmkt;
use \Eloquent;
class SolicitudeClient extends Eloquent{


    protected $table = 'DMKT_RG_SOLICITUD_CLIENTES';
    protected $primaryKey = 'IDSOLICITUD_CLIENTES';
    public $timestamps = false;

    function searchId(){
        $lastId = SolicitudeClient::orderBy('idsolicitud_clientes', 'DESC')->first();
        if($lastId == null){
            return 0;
        }else{
            return $lastId->idsolicitud_clientes;
        }

    }

    function client(){

        return $this->hasOne('Dmkt\Client','clcodigo','idcliente');
    }

}