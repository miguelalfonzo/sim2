<?php

namespace Dmkt;

use \Eloquent;

class Rm extends Eloquent
{

    protected $table = 'OUTDVP.DMKT_RG_RM';
    protected $primaryKey = 'idrm';

    function searchId()
    {
        $lastId = Rm::orderBy('idrm', 'DESC')->first();
        if($lastId == null)
            return 0;
        else
            return $lastId->idrm;
    }

    protected function getFullNameAttribute()
    {
        return substr( $this->attributes['nombres'] , 0 , 1 ).'. '.$this->attributes['apellidos'];
    }

    function rmSup()
    {
        return $this->belongsTo('Dmkt\Sup','idsup','idsup');
    }

    function user(){
        return $this->hasOne('User','id','iduser');
    }

}