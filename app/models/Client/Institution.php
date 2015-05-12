<?php

namespace Client;
use \Eloquent;

class Institution extends Eloquent
{

    protected $table = 'FICPE.PERSONAJUR';
    protected $primaryKey = 'pejcodpers';

    protected function getFullNameAttribute()
    {
        return 'INSTITUCION: '.$this->attributes[ 'pejrazon' ];
    }
}