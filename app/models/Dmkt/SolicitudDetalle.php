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
        if( is_null( $lastId ) )
            return 0;
        else
        	return $lastId->id;
    }

    public function periodo()
    {
        return $this->hasOne( 'Dmkt\Solicitud\Periodo' , 'id' , 'id_periodo' );
    }

    protected function reason()
    {
        return $this->hasOne('Dmkt\Reason','id','id_motivo');
    }

    protected function typeMoney()
    {
    	return $this->hasOne('Common\TypeMoney','id', 'id_moneda' );
    }

    protected function typePayment()
    {
        return $this->hasOne('Common\TypePayment','id','id_pago');
    }

    protected function deposit()
    {
        return $this->hasOne('Common\Deposit','id','id_deposito');
    }
    
    public function fondo()
    {
        return $this->hasOne('Common\Fondo','id','id_fondo');
    }
}
