<?php

namespace Client;
use \Eloquent;

class Institution extends Eloquent
{

    protected $table = 'FICPE_PERSONAJUR';
    protected $primaryKey = 'pejcodpers';

    protected function getFullNameAttribute()
    {
        return 'INSTITUCION: ' . $this->pejrazon;
    }
}