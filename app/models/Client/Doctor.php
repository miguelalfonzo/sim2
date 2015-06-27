<?php

namespace Client;
use \Eloquent;

class Doctor extends Eloquent
{
    protected $table = 'FICPE_PERSONAFIS';
    protected $primaryKey = 'pefcodpers';

    protected function getFullNameAttribute()
    {
        return 'DOCTOR: '.$this->attributes[ 'pefnrodoc1' ].'-'.$this->attributes[ 'pefnombres' ].' '.$this->attributes[ 'pefpaterno'].' '.$this->attributes[ 'pefmaterno' ];
    }
} 