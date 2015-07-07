<?php

namespace Client;
use \Eloquent;

class Pharmacy extends Eloquent
{

    protected $table = 'FICPEF.PERSONAJUR';
    protected $primaryKey = 'pejcodpers';

    protected function getFullNameAttribute()
    {
        return 'FARMACIA: '.$this->attributes[ 'pejnrodoc' ].'-'.$this->attributes[ 'pejrazon' ];
    }

}