<?php

namespace Dmkt;

use Eloquent;

class SolicitudeDetalle extends Eloquent
{
	protected $table = 'DMKT2_RG_SOLICITUD_DETALLE';
    protected $primaryKey = 'id';    
 
    public function searchId()
    {
        $lastId = SolicitudeDetalle::orderBy('id', 'DESC')->first();
        if($lastId == null)
            return 0;
        else
        	return $lastId->id;
    }

    public function periodo()
    {
        return $this->hasOne('Dmkt\Solicitud\Periodo','id','idperiodo');
    }

    protected function typeReason()
    {
        return $this->hasOne('Dmkt\TypeSolicitude','id','idmotivo');
    }

    protected function typeMoney()
    {
    	return $this->hasOne('Common\TypeMoney','id', 'idmoneda' );
    }

    protected function typePayment()
    {
        return $this->hasOne('Common\TypePayment','id','idpago');
    }

    protected function typeRetention()
    {
        return $this->hasOne('Dmkt\TypeRetention','id','idretencion');
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
