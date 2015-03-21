<?php

namespace Dmkt;
use \Eloquent;

class Client extends Eloquent{

    protected $fillable = array('clcodigo', 'clnombre');
    protected $table = 'VTA.CLIENTES';
    protected $primaryKey = 'CL_CODIGO';


}

