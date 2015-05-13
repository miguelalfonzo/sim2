<?php

namespace Dmkt;

use Eloquent;

class SolicitudDetalle extends Eloquent
{
	protected $table = 'SOLICITUD_DETALLE';
    protected $primaryKey = 'id';    
 
    public function lastId()
    {
        $lastId = SolicitudDetalle::orderBy('id', 'DESC')->first();
        if( $lastId == null )
            return 0;
        else
        	return $lastId->id;
    }

    public function periodo()
    {
        return $this->hasOne('Dmkt\Solicitud\Periodo','id','idperiodo');
    }

    protected function reason()
    {
        return $this->hasOne('Dmkt\SolicitudReason','id','idmotivo');
    }

    protected function typeMoney()
    {
    	return $this->hasOne('Common\TypeMoney','id', 'idmoneda' );
    }

    protected function typePayment()
    {
        return $this->hasOne('Common\TypePayment','id','idpago');
    }

    protected function deposit()
    {
        return $this->hasOne('Common\Deposit','id','iddeposito');
    }
    
    public function fondo()
    {
        return $this->hasOne('Common\Fondo','id','idfondo');
    }
}
