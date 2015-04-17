<?php

namespace Dmkt\Solicitud;
use \Eloquent;

class Periodo extends Eloquent
{
    protected $table = 'DMKT_RG_PERIODO';
    protected $primaryKey = 'id';

    public function searchId()
    {
        $lastId = Periodo::orderBy('id', 'DESC')->first();
        if( $lastId == null )
            return 0;
        else
            return $lastId->id;
    }

    protected static function inhabilitar( $periodo )
    {
        Periodo::where('periodo' , $periodo )->where('status',ACTIVE)->update( array( 'status' => 3) );
    }

}
    