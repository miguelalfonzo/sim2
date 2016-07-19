<?php

namespace Client;
use \Eloquent;

class Pharmacy extends Eloquent
{

    protected $table = TB_FARMACIA;
    protected $primaryKey = 'pejcodpers';

    protected function getFullNameAttribute()
    {
        return $this->attributes[ 'pejnrodoc' ].'-'.$this->attributes[ 'pejrazon' ];
    }

    protected function getFullNameAttribute()
    {
        return $this->pejrazon;
    }

}