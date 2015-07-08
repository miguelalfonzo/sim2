<?php

namespace Users;

use \Eloquent;

class Visitador extends Eloquent 
{
    protected $table = 'FICPE.VISITADOR';
    protected $primaryKey = 'VISVISITADOR';

    protected function cuenta()
    {
    	return $this->hasOne('Dmkt\CtaRm','codbeneficiario','vislegajo');
    }

    protected function sup()
    {
    	return $this->belongsToMany('Users\Supervisor','ficpe.linsupvis','lsvvisitador','lsvsupervisor','visvisitador');
    }
}