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
    /*protected function pendHist()
    {
        return $this->hasOne('System\SolicitudeHistory','idsolicitude','id')->where( 'status_to' , PENDIENTE );
    }*/

    public function asignedTo()
    {
        return $this->hasOne('User','id','iduserasigned');
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

    protected function createdBy(){
        return $this->belongsTo('User','created_by');
    }

    public function gerente()
    {
        return $this->hasOne('Dmkt\SolicitudeGer','idsolicitud','id');
    }

    function state(){

        return $this->hasOne('Common\State','idestado','idestado');
    }

    function reasonSolicitude(){
        return $this->hasOne('Dmkt\SolicitudReason','id','idtiposolicitud');
    }

    
    
    function gastos(){
        return $this->hasMany('Expense\Expense','idsolicitud','id');
    }

   

    function response(){
        return $this->belongsTo('User','iduserasigned');
    }

    
    
    function typeRetention(){
        return $this->hasOne('Dmkt\TypeRetention','id','idtiporetencion');
    }

    function aproved(){
        return $this->hasOne('User','id', 'idaproved');
    }

    function deposit(){
        return $this->hasOne('Common\Deposit','id', 'iddeposito');
    }

    protected function rm(){
        return $this->hasOne('Dmkt\Rm','iduser','created_by')->select('nombres,apellidos,iduser');
    }

    protected function sup(){
        return $this->hasOne('Dmkt\Sup','iduser','created_by')->select('nombres,apellidos,iduser,idsup');;
    }

    function aprovedSup(){
        return $this->hasOne('Dmkt\Sup','iduser','idaproved');
    }

    function aprovedGerProd(){
        return $this->hasOne('Dmkt\Manager','iduser','idaproved');
    }

    function etiqueta(){
        return $this->hasOne('Dmkt\Label','id','idetiqueta');
    }
}