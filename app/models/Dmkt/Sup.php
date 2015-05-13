<?php

namespace Dmkt;

use \Eloquent;

class Sup extends Eloquent
{


    protected $table = 'OUTDVP.DMKT_RG_SUPERVISOR';
    protected $primaryKey = 'idsup';

    protected function getFullNameAttribute()
    {
        return substr( $this->attributes['nombres'] , 0 , 1 ).'. '.$this->attributes['apellidos'];
    }

    function lastId()
    {
        $lastId = Sup::orderBy('idsup', 'DESC')->first();
        if($lastId == null)
            return 0;
        else
            return $lastId->idsup;
    }

    public function reps()
    {
        return $this->hasMany('Dmkt\Rm','idsup','idsup');
    }
}