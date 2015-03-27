<?php

namespace Dmkt;
use \Eloquent;
use \Dmkt\Rm;

class FondoInstitucional extends Eloquent
{
    protected $table = 'DMKT_RG_FONDOINSTITUCIONAL';
    protected $primaryKey = 'idfondo';

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

    public function deposit()
    {
        return $this->hasOne('Common\Deposit','idfondo','idfondo');
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

    function histories(){
        return $this->hasMany('System\SolicitudeHistory','idsolicitude');
    }
}