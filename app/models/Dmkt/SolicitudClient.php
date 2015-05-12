<?php

namespace Dmkt;
use \Eloquent;

class SolicitudClient extends Eloquent
{
    protected $table = 'DMKT_RG_SOLICITUD_CLIENTE';
    protected $primaryKey = 'id';

    public function lastId()
    {
        $lastId = SolicitudClient::orderBy('id', 'DESC')->first();
        if($lastId == null)
            return 0;
        else
            return $lastId->id;
    }

    protected function doctor()
    {
        return $this->hasOne('Client\Doctor','pefcodpers','idcliente');
    }

    protected function institution()
    {
        return $this->hasOne('Client\Institution','pejcodpers','idcliente');
    }

    protected function pharmacy()
    {
        return $this->hasOne('Client\Pharmacy' , 'pejcodpers' , 'idcliente');
    }

    protected function distrimedclient()
    {
        return $this->hasOne('Client\DistrimedClient' , 'clcodigo' , 'idcliente');
    }

}