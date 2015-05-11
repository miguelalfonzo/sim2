<?php

namespace Dmkt;
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
        Periodo::where('periodo' , $periodo )->where('status',ACTIVE)->where( 'idtiposolicitud' , SOL_INST )->update( array( 'status' => 3) );
    }

    protected static function periodoInst( $periodo )
    {
        return Periodo::where( 'periodo' , $periodo )->where( 'idtiposolicitud' , SOL_INST )->first();    
    }

}
    