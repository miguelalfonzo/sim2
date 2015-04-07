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

    public function typeMoney()
    {
    	return $this->hasOne('Common\TypeMoney','idtipomoneda', 'idmoneda' );
    }

    public function prueba()
    {
    		$sol = SolicitudeDetalle::first();
    		return $sol->type;
    		return $sol->id_tipo_moneda;
    }
}
