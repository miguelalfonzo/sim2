<?php

namespace Dmkt;

use Eloquent;

class SolicitudDetalle extends Eloquent
{
	protected $table = 'SOLICITUD_DETALLE';
    protected $primaryKey = 'id';    
 
    protected function getMontoActualAttribute()
    {
        $jDetalle = json_decode( $this->detalle );
        if ( isset( $jDetalle->monto_aprobado ) )
            return $jDetalle->monto_aprobado;
        else if ( isset( $jDetalle->monto_aceptado ) )
            return $jDetalle->monto_aceptado;
        else if ( isset( $jDetalle->monto_validado ) )
            return $jDetalle->monto_validado;
        else if ( isset( $jDetalle->monto_solicitado ) )
            return $jDetalle->monto_solicitado;
        else
            return 0;
    }

    protected function getFechaEntregaAttribute()
    {
        return json_decode( $this->detalle )->fecha_entrega;
    }

    protected function getNumRucAttribute()
    {
        return json_decode( $this->detalle )->num_ruc;
    }

    protected function getMontoFacturaAttribute()
    {
        return json_decode( $this->detalle )->monto_factura;
    }

    protected function getImageAttribute()
    {
        return json_decode( $this->detalle )->image;
    }

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
