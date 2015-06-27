<?php

namespace Dmkt;
use \Eloquent;

class SolicitudClient extends Eloquent
{
    protected $table = 'SIM_SOLICITUD_CLIENTE';
    protected $primaryKey = 'id';

    public function lastId()
    {
        $lastId = SolicitudClient::orderBy('id', 'DESC')->first();
        if( is_null( $lastId ) )
            return 0;
        else
            return $lastId->id;
    }

    protected function doctor()
    {
        return $this->hasOne('Client\Doctor','pefcodpers','id_cliente');
    }

    protected function institution()
    {
        return $this->hasOne('Client\Institution','pejcodpers','id_cliente');
    }

    protected function pharmacy()
    {
        return $this->hasOne('Client\Pharmacy' , 'pejcodpers' , 'id_cliente');
    }

    protected function warehouse()
    {
        return $this->hasOne('Client\DistrimedClient' , 'clcodigo' , 'id_cliente')->where( 'clclase' , 1 )->where( 'clestado' , 1 );
    }    

    protected function distributor()
    {
        return $this->hasOne('Client\DistrimedClient' , 'clcodigo' , 'id_cliente')->where( 'clclase' , 6 )->where( 'clestado' , 1 );
    }

    protected function clientType()
    {
        return $this->hasOne( 'Client\ClientType' , 'id' , 'id_tipo_cliente' );
    }
}