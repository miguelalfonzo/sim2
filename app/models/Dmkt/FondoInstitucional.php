<?php

namespace Dmkt;
use \Eloquent;
use \Dmkt\Rm;

class FondoInstitucional extends Eloquent
{
    protected $table = 'DMKT_RG_FONDOINSTITUCIONAL';
    //public $primaryKey = 'idfondo';

    function searchId()
    {
        $lastId = FondoInstitucional::orderBy('idfondo', 'DESC')->first();
        if($lastId == null)
            return 0;
        else
            return $lastId->idfondo;
    }

    public function monthYear($period) 
    {
        $month = substr($period, -2);
        $year = substr($period, 0, 4);
        return $month.'/'.$year;
    }

    public function iduser($idrm)
    {
        $response = Rm::where('idrm',$idrm)->first();
        if (is_null($response))
            return $response;
        else
            return $response->iduser;
    }

    public function state()
    {
        return $this->hasOne('Common\State','idestado','estado');
    }

    public function histories()
    {
        return $this->hasMany('System\SolicitudeHistory','idsolicitude');
    }

    protected function deposit()
    {
        return $this->hasOne('Common\Deposit','iddeposito', 'iddeposito');
    }
    
    protected function account(){
        return  $this->hasOne('Common\Fondo','idfondo','idcuenta');
    }

    protected function typemoney(){
        return $this->hasOne('Common\TypeMoney','idtipomoneda','tipo_moneda');
    }

    protected function rep(){
        return $this->hasOne('Dmkt\Rm','idrm','idrm');
    }

    protected function ager()
    {
        return $this->hasOne('User','id','iduser');
    }
}