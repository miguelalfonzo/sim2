<?php

namespace Dmkt;

use \Eloquent;

class Solicitud extends Eloquent
{
    protected $table = 'SOLICITUD';
    protected $primaryKey = 'id';

    protected function getFecOriginAttribute()
    {
        return $this->attributes['created_at'];
    }

    protected function getCreatedAtAttribute( $attr )
    {
        return \Carbon\Carbon::parse( $attr )->format('Y-m-d H:i');
    }

    public function lastId()
    {
        $lastId = Solicitud::orderBy('id', 'DESC')->first();
        if( $lastId == null )
            return 0;
        else
            return $lastId->id;
    }

    protected static function solInst( $periodo )
    {
        return Solicitud::orderBy('id','desc')->whereHas('detalle' , function ($q ) use ( $periodo )
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
        return $this->hasMany( 'System\SolicitudHistory' , 'idsolicitude' , 'id' )->where( 'status_to' , GASTO_HABILITADO );
    }

    public function acceptHist()
    {
        return $this->hasOne('System\SolicitudHistory','idsolicitude','id')->where( 'status_to' , ACEPTADO );
    }

    public function rejectedHist(){
        return $this->hasOne('System\SolicitudHistory','idsolicitude','id')->where( 'status_to' , RECHAZADO );
    }

    public function histories(){
        return $this->hasMany('System\SolicitudHistory','idsolicitude');
    }

    public function detalle()
    {
        return $this->hasOne('Dmkt\SolicitudDetalle','id','iddetalle');
    }


    /* PROTECTED RELATIOSNSHIPS */

    protected function registerHist()
    {
        return $this->hasOne('System\SolicitudHistory','idsolicitude','id')->where('status_to' , REGISTRADO );
    }

    protected function typeSolicitude()
    {
        return $this->hasOne('Dmkt\SolicitudType','id','idtiposolicitud');
    }

    protected function families(){
        return $this->hasMany('Dmkt\SolicitudFamily','idsolicitud','id');
    }

    protected function clients(){
        return $this->hasMany('Dmkt\SolicitudClient','idsolicitud','id');
    }

    protected function createdBy()
    {
        return $this->belongsTo('User','created_by');
    }

    public function gerente()
    {
        return $this->hasOne('Dmkt\SolicitudGer','idsolicitud','id');
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

    protected function actividad()
    {
        return $this->hasOne('Dmkt\Activity','id','idetiqueta');
    }

    protected function expenses()
    {
        return $this->hasMany( '\Expense\Expense' , 'idsolicitud' , 'id' )->orderBy( 'updated_at' , 'desc');
    }

    protected function baseEntry()
    {
        return $this->hasOne( 'Expense\Entry' , 'idsolicitud' )->where( 'd_c' , ASIENTO_GASTO_BASE );
    }

    protected function depositEntrys()
    {
        return $this->hasMany( 'Expense\Entry' , 'idsolicitud' )->where( 'd_c' , ASIENTO_GASTO_DEPOSITO );
    }
}