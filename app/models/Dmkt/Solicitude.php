<?php

namespace Dmkt;

use \Eloquent;

class Solicitude extends Eloquent{


    protected $table = 'DMKT_RG_SOLICITUD';
    protected $primaryKey = 'id';

    protected function getFecOriginAttribute()
    {
        return $this->attributes['created_at'];
    }

    protected function getCreatedAtAttribute( $attr )
    {
        return \Carbon\Carbon::parse( $attr )->format('Y-m-d h:i');
    }

    public function searchId()
    {
        $lastId = Solicitude::orderBy('id', 'DESC')->first();
        if($lastId == null)
            return 0;
        else
            return $lastId->id;
    }

    protected static function solInst( $periodo )
    {
        return Solicitude::orderBy('id','desc')->whereHas('detalle' , function ($q ) use ( $periodo )
        {
            $q->whereHas('periodo' , function ( $t ) use ( $periodo )
            {
                $t->where('periodo',$periodo);
            });
        })->where( 'idestado' , '<>' , CANCELADO )->get();
    }

    /* PUBLIC RELATIONSHIPS */

    public function state()
    {
        return $this->hasOne('Common\State','idestado','idestado');
    }

    public function asignedTo()
    {
        return $this->hasOne('User','id','iduserasigned');
    }

    protected function advanceSeatHist()
    {
        return $this->hasMany( 'System\SolicitudeHistory' , 'idsolicitude' , 'id' )->where( 'status_to' , GASTO_HABILITADO );
    }

    public function acceptHist()
    {
        return $this->hasOne('System\SolicitudeHistory','idsolicitude','id')->where( 'status_to' , ACEPTADO );
    }

    public function histories(){
        return $this->hasMany('System\SolicitudeHistory','idsolicitude');
    }

    public function detalle()
    {
        return $this->hasOne('Dmkt\SolicitudeDetalle','id','iddetalle');
    }


    /* PROTECTED RELATIOSNSHIPS */

    protected function registerHist()
    {
        return $this->hasOne('System\SolicitudeHistory','idsolicitude','id')->where('status_to' , REGISTRADO );
    }

    protected function typeSolicitude()
    {
        return $this->hasOne('Dmkt\SolicitudeType','id','idtiposolicitud');
    }

    protected function families(){
        return $this->hasMany('Dmkt\SolicitudeFamily','idsolicitud','id');
    }

    protected function clients(){
        return $this->hasMany('Dmkt\SolicitudeClient','idsolicitud','id');
    }

    protected function createdBy()
    {
        return $this->belongsTo('User','created_by');
    }

    public function gerente()
    {
        return $this->hasOne('Dmkt\SolicitudeGer','idsolicitud','id');
    }

    

    protected function reasonSolicitude()
    {
        return $this->hasOne('Dmkt\SolicitudReason','id','idtiposolicitud');
    }

    protected function response()
    {
        return $this->belongsTo('User','iduserasigned');
    }

    protected function rm()
    {
        return $this->hasOne('Dmkt\Rm','iduser','created_by')->select('nombres,apellidos,iduser');
    }

    protected function sup()
    {
        return $this->hasOne('Dmkt\Sup','iduser','created_by')->select('nombres,apellidos,iduser,idsup');;
    }

    protected function etiqueta()
    {
        return $this->hasOne('Dmkt\Label','id','idetiqueta');
    }

    protected function expenses()
    {
        return $this->hasMany( '\Expense\Expense' , 'idsolicitud' , 'id' )->orderBy( 'updated_at' , 'desc');
    }

    protected function baseEntrys()
    {
        return $this->hasMany( 'Expense\Entry' , 'idsolicitud' )->where( 'd_c' , ASIENTO_GASTO_BASE);
    }
}