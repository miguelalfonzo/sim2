<?php

namespace Dmkt;

use \Eloquent;

class Solicitude extends Eloquent{


    protected $table = 'DMKT2_RG_SOLICITUD';
    protected $primaryKey = 'id';
    

    public function searchId()
    {
        $lastId = Solicitude::orderBy('id', 'DESC')->first();
        if($lastId == null)
            return 0;
        else
            return $lastId->id;
    }

    public function asignedTo()
    {
        return $this->hasOne('User','id','iduserasigned');
    }

    public function acceptHist()
    {
        return $this->hasOne('System\SolicitudeHistory','idsolicitude','id')->where('status_to',ACEPTADO);
    }

    public function histories(){
        return $this->hasMany('System\SolicitudeHistory','idsolicitude');
    }

    protected function detalle()
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

    function subtype(){

        return  $this->hasOne('Common\Fondo','idfondo','idfondo');
    }

    function state(){

        return $this->hasOne('Common\State','idestado','idestado');
    }

    function reasonSolicitude(){
        return $this->hasOne('Dmkt\TypeSolicitude','idtiposolicitud','idtiposolicitud');
    }

    
    
    function gastos(){
        return $this->hasMany('Expense\Expense','idsolicitud','id');
    }

   

    function response(){
        return $this->belongsTo('User','iduserasigned');
    }

    
    
    function typeRetention(){
        return $this->hasOne('Dmkt\TypeRetention','idtiporetencion','idtiporetencion');
    }

    function aproved(){
        return $this->hasOne('User','id', 'idaproved');
    }

    function deposit(){
        return $this->hasOne('Common\Deposit','iddeposito', 'iddeposito');
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

    /*protected function solicitudsRange($user,$estado,$start,$end)
    {
        $solicituds = Solicitude::leftJoin('dmkt_rg_sub_estado as se','se.idestado','s.estado')
        ->leftJoin('dmkt_rg_estado as e','e.id','se.idstate')
        ->where('s.iduser',$user)
        ->select('s.idsolicitud','s.typemoney','created_at','s.typesolicitude','s.estado','s.asiento','s.idresponse','s.derive','s.blocked')
        if ($estado != 10)
        {

        }
    }*/
}