<?php


namespace Dmkt;
use \Eloquent;
class SolicitudeClient extends Eloquent{


    protected $table = 'DMKT_RG_SOLICITUD_CLIENTE';
    protected $primaryKey = 'ID';

    public function searchId()
    {
        $lastId = SolicitudeClient::orderBy('id', 'DESC')->first();
        if($lastId == null)
            return 0;
        else
            return $lastId->id;
    }

    function client()
    {
        return $this->hasOne('Dmkt\Client','clcodigo','idcliente');
    }

    function doctors()
    {
        return $this->hasOne('Dmkt\Doctor','pefcodpers','idcliente');
    }

    function institutes()
    {
        return $this->hasOne('Dmkt\Institute','pejcodpers','idcliente');
    }  

}