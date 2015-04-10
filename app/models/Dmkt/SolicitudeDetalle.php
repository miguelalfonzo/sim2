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

    protected function typeReason()
    {
        return $this->hasOne('Dmkt\TypeSolicitude','id','idmotivo');
    }

    protected function typeMoney()
    {
    	return $this->hasOne('Common\TypeMoney','idtipomoneda', 'idmoneda' );
    }

    protected function typePayment()
    {
        return $this->hasOne('Common\TypePayment','idtipopago','idpago');
    }

    protected function typeRetention()
    {
        return $this->hasOne('Dmkt\TypeRetention','idtiporetencion','idretencion');
    }

    public function prueba()
    {
    		$sol = SolicitudeDetalle::first();
    		return $sol->type;
    		return $sol->id_tipo_moneda;
    }

    public function fondo(){
        return $this->hasOne('Common\Fondo','id','idfondo');

    }
}
