<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 07/08/14
 * Time: 01:47 PM
 */


namespace Dmkt;
use \Eloquent;


class Solicitude extends Eloquent{


    protected $table = 'DMKT_RG_SOLICITUD';
    protected $primaryKey = 'IDSOLICITUD';

    function searchId(){

        $lastId = Solicitude::orderBy('idsolicitud', 'DESC')->first();
        if($lastId == null){
            return 0;
        }else{
            return $lastId->idsolicitud;
        }

    }
    function subtype(){

        return  $this->hasOne('Common\Fondo','idfondo','idfondo');
    }

    function state(){

        return $this->hasOne('Common\State','idestado','estado');
    }

    function typemoney(){

        return $this->hasOne('Common\TypeMoney','idtipomoneda','tipo_moneda');
    }

    function typesolicitude(){
        return $this->hasOne('Dmkt\TypeSolicitude','idtiposolicitud','idtiposolicitud');
    }

    function families(){
        return $this->hasMany('Dmkt\SolicitudeFamily','idsolicitud','idsolicitud');
    }
    function clients(){
        return $this->hasMany('Dmkt\SolicitudeClient','idsolicitud','idsolicitud');
    }
    function gastos(){
        return $this->hasMany('Expense\Expense','idsolicitud','idsolicitud');
    }

    function user(){
        return $this->belongsTo('User','iduser');
    }

    function typePayment(){
        return $this->hasOne('Common\TypePayment','idtipopago','idtipopago');

    }
    function fondo(){
        return $this->hasOne('Common\Fondo','idfondo','idfondo');

    }
}
