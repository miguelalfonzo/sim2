<?php

namespace Dmkt;

use Eloquent;

class SolicitudType extends Eloquent
{
	protected $table = TB_SOLICITUD_TIPO;
    protected $primaryKey = 'id';

    protected static function getNormalTypes()
    {
    	return SolicitudType::where( 'code' , '<>' , 'F' )->orderBy( 'id' )->get();
    }
}
 