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
        if( is_null( $lastId ) )
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
        return $this->hasOne( 'Common\State' , 'id' , 'id_estado' );
    }

    public function asignedTo()
    {
        return $this->hasOne('User','id','iduserasigned');
    }

    protected function advanceSeatHist()
    {
        return $this->hasMany( 'System\SolicitudHistory' , 'id_solicitud' , 'id' )->where( 'status_to' , GASTO_HABILITADO );
    }

    public function acceptHist()
    {
        return $this->hasOne('System\SolicitudHistory','id_solicitud','id')->where( 'status_to' , ACEPTADO );
    }

    public function rejectedHist(){
        return $this->hasOne('System\SolicitudHistory','id_solicitud','id')->where( 'status_to' , RECHAZADO );
    }

    public function histories(){
        return $this->hasMany('System\SolicitudHistory','id_solicitud');
    }

    public function detalle()
    {
        return $this->hasOne( 'Dmkt\SolicitudDetalle' , 'id' , 'id_detalle' );
    }


    /* PROTECTED RELATIOSNSHIPS */

    protected function registerHist()
    {
        return $this->hasOne('System\SolicitudHistory','id_solicitud','id')->where('status_to' , REGISTRADO );
    }

    protected function typeSolicitude()
    {
        return $this->hasOne('Dmkt\SolicitudType','id','idtiposolicitud');
    }

    protected function products(){
        return $this->hasMany( 'Dmkt\SolicitudProduct' , 'id_solicitud' , 'id' );
    }

    protected function clients(){
        return $this->hasMany( 'Dmkt\SolicitudClient' , 'id_solicitud' , 'id' );
    }

    protected function createdBy()
    {
        return $this->belongsTo('User','created_by');
    }

    public function gerente()
    {
        return $this->hasOne( 'Dmkt\SolicitudGer' , 'id_solicitud' , 'id' );
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

    protected function activity()
    {
        return $this->hasOne('Dmkt\Activity','id','id_actividad');
    }

    protected function expenses()
    {
        return $this->hasMany( '\Expense\Expense' , 'id_solicitud' , 'id' )->orderBy( 'updated_at' , 'desc');
    }

    protected function baseEntry()
    {
        return $this->hasOne( 'Expense\Entry' , 'id_solicitud' )->where( 'd_c' , ASIENTO_GASTO_BASE );
    }

    protected function depositEntrys()
    {
        return $this->hasMany( 'Expense\Entry' , 'id_solicitud' )->where( 'd_c' , ASIENTO_GASTO_DEPOSITO );
    }
}