<?php

namespace Dmkt;
use \Eloquent;

class SolicitudActivity extends Eloquent
{
    protected $table = 'DMKT_RG_ETIQUETA';
    protected $primaryKey = 'id';

    protected static function order()
    {
    	return SolicitudActivity::orderBy('id','asc')->get();
    }
}