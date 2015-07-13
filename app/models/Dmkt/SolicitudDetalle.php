<?php

namespace Dmkt;

use Eloquent;

class SolicitudDetalle extends Eloquent
{
	protected $table = 'SOLICITUD_DETALLE';
    protected $primaryKey = 'id';    
 
    protected function getTccAttribute()
    {
        return json_decode( $this->detalle )->tcc;
    }

    protected function getTcvAttribute()
    {
        return json_decode( $this->detalle )->tcv;
    }

    protected function getNumCuentaAttribute()
    {
        return json_decode( $this->detalle )->num_cuenta;
    }

    protected function getSupervisorAttribute()
    {
        $idSup = json_decode( $this->detalle )->supervisor;
        return \Users\Sup::where( 'iduser' , $idSup )->first()->full_name;
    }    

    protected function getMontoActualAttribute()
    {
        $jDetalle = json_decode( $this->detalle );
        if ( isset( $jDetalle->monto_aprobado ) )
            return $jDetalle->monto_aprobado;
        else if ( isset( $jDetalle->monto_aceptado ) )
            return $jDetalle->monto_aceptado;
        else if ( isset( $jDetalle->monto_solicitado ) )
            return $jDetalle->monto_solicitado;
        else
            return null;
    }

    protected function getMontoSolicitadoAttribute()
    {
        return json_decode( $this->detalle )->monto_solicitado;
    }

    protected function getFechaEntregaAttribute()
    {
        $jDetalle = json_decode( $this->detalle );
        if ( isset( $jDetalle->fecha_entrega ) )
            return $jDetalle->fecha_entrega;
        else
            return $this->periodo->aniomes;
    }

    protected function getNumRucAttribute()
    {
        $jDetalle = json_decode( $this->detalle );
        if ( isset( $jDetalle->num_ruc ) )
            return $jDetalle->num_ruc;
        else
            return 0;
    }

    protected function getMontoFacturaAttribute()
    {
        $jDetalle = json_decode( $this->detalle );
        if ( isset( $jDetalle->monto_factura ) )
            return $jDetalle->monto_factura;
        else
            return null;
    }

    protected function getImageAttribute()
    {
        $jDetalle = json_decode( $this->detalle );
        if ( isset( $jDetalle->image ) )
            return $jDetalle->image;
        else
            return null;
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
        return $this->hasOne( 'Dmkt\Periodo' , 'id' , 'id_periodo' );
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

    protected function sup()
    {
        return $this->hasOne( 'Users\Sup' , 'iduser' , 'supervisor' );
    }
}