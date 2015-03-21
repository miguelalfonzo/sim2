<?php

namespace Dmkt;
use \Eloquent;

class Doctor extends Eloquent{

    protected $fillable = array('clcodigo', 'clnombre');
    protected $table = 'FICPE.PERSONAFIS';
    protected $primaryKey = 'PEFCODPERS';


}