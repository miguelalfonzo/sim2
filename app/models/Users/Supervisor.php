<?php

namespace Users;

use \Eloquent;

class Supervisor extends Eloquent 
{
    protected $table = 'FICPE_SUPERVISOR';
    protected $primaryKey = 'SUPSUPERVISOR';

    protected function reps()
    {
    	return $this->belongsToMany('Users\Visitador','ficpe.linsupvis','lsvvisitador','lsvsupervisor');
    }
}
